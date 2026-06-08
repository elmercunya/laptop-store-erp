<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Client;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreSaleRequest;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $query = Sale::query();

        if($busqueda) {
            $query->where(function($q) use($busqueda) {
                $q->where('number', 'LIKE', '%'.$busqueda.'%')->orWhereHas('client', function($q2) use($busqueda) {
                    $q2->where('name', 'LIKE', '%'.$busqueda.'%');
                });
            });
        }


        $sales = $query->with('client')->latest()->paginate(3);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vouchers = ['nota de venta', 'boleta', 'factura'];

        $clients = Client::all();

        $units = Unit::where('status', 'disponible')->with('product')->paginate(15);

        return view('sales.create', compact('vouchers', 'clients', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        
        $datos = $request->validated();

        DB::beginTransaction();

        try {
            $unit_ids = $datos['unit_ids'];

            $total = 0;

            foreach($unit_ids as $unitId) {
                $unit = Unit::with('product')->find($unitId);

                $total = $total + $unit->product->sale_price;
            }

            $subtotal = $total / 1.18;

            $igv = $total - $subtotal;

            $date = Carbon::now()->toDateString();

            $type = $datos['voucher'];

            $lastSale = Sale::where('voucher', $type)->lockForUpdate()->latest('number')->first();
            $nextNumber = $lastSale ? intval(str_replace("V-", "", $lastSale->number)) + 1 : 1;
            $number = 'V-' .str_pad($nextNumber, 6, 0, STR_PAD_LEFT);

            $sale = Sale::create([
                'client_id' => $datos['client_id'],
                'voucher' => $type,
                'number' => $number,
                'date' => $date,
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total
            ]);

            sort($unit_ids);
            
            foreach($unit_ids as $unitId) {

                $unit = Unit::with('product')->where('id', $unitId)->lockForUpdate()->firstOrFail();

                if($unit->status !== 'disponible') {
                    throw new \Exception("La unidad con serie {$unit->serial_number} ya no está disponible");
                }

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'unit_id' => $unitId,
                    'price' => $unit->product->sale_price,
                ]);

                $unit->update([
                    'status' => 'vendido',
                ]);

            }

            DB::commit();

            return redirect()->route('sales.index')->with('message', 'Venta registrada con éxito');
            

        } catch(\Exception $e) {
            DB::rollback();
            return back()->with('message', 'Hubo un error al procesar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with(['client', 'saleDetails.unit.product'])->findOrFail($id);

        return view('sales.show', compact('sale'));
    }

    public function destroy(string $id)
    {

        if(Auth::user()->role !== 'admin') {
            return redirect()->route('sales.index');
        }

        $sale = Sale::with('saleDetails')->findOrFail($id);

        if($sale->status === 'ANULADA') {
            return redirect()->back()->with('message', 'La venta ya está anulada');
        }

        DB::beginTransaction();

        try {
            
            $sale->update([
                'status' => 'ANULADA',
            ]);

            $unitIds = $sale->saleDetails->pluck('unit_id');

            Unit::whereIn('id', $unitIds)->update(['status' => 'disponible']);
            
            DB::commit();

            return redirect()->route('sales.index')->with('message', 'Venta anulada correctamente y laptops devueltas al inventario.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('message', 'Hubo un problema al anular: '. $e->getMessage());
        }
    }

    public function downloadPdf($id) {
        $sale = Sale::with(['client', 'SaleDetails.unit.product'])->findOrFail($id);

        $pdf = Pdf::loadView('sales.pdf', compact('sale'));

        return $pdf->stream('Comprobante_' .$sale->number. '.pdf');

    }

    public function searchUnits(Request $request)
    {
        $term = $request->query('q');

        if (!$term) {
            return response()->json([]);
        }

        $units = Unit::with('product')
        ->where('status', 'disponible')
        ->where(function($query) use ($term) {
            $query->whereHas('product', function($q) use ($term) {
                // Buscamos en el nombre del producto
                $q->where('name', 'LIKE', '%' . $term . '%');
            })
            // O buscamos en el número de serie de la unidad
            ->orWhere('serial_number', 'LIKE', '%' . $term . '%');
        })
        ->take(10)
        ->get();

    return response()->json($units);
    }

    public function searchClients(Request $request) {
        $term = $request->query('q');

        if(!$term) {
            return response()->json([]);
        }

        $client = Client::where(function($q) use($term) {
            $q->where('name', 'LIKE', '%'.$term.'%')->orWhere('document_number', 'LIKE', '%'.$term.'%');
        })->take(10)->get();

        return response()->json($client);;
    }

    public function export() {
        return Excel::download(new SalesExport, 'reporte_ventas'. now()->format('d-m-Y'). '.xlsx');
    }
}
