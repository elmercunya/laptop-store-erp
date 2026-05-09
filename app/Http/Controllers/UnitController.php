<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $busqueda = $request->input('busqueda');

        $query = Unit::query();

        if($busqueda) {
            $query->where(function($q) use($busqueda) {
                $q->where('serial_number', 'LIKE', '%' . $busqueda. '%')->orWhereHas('product', function ($q2) use ($busqueda) {
              $q2->where('name', 'LIKE', '%'.$busqueda.'%');
          });
            });
        }

        $units = $query->paginate(10);

        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();

        return view('units.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $data = $request->validated();

        Unit::create($data);

        return redirect()->route('units.index')->with('message', 'Unidad creada correctamente');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $unit = Unit::find($id);
        
        $products = Product::all();

        return view('units.edit', compact('unit', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        
        $data = $request->validated();

        $data['status'] = 'disponible';

        $unit->update($data);

        return redirect()->route('units.index')->with('message', 'Unidad actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::find($id);

        $unit->delete();

        return redirect()->route('units.index')->with('message', 'Unidad eliminada correctamente');
    }
    
}
