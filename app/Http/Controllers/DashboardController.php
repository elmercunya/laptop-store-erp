<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index() {

        if(Auth::user()->role !== 'admin') {
            return redirect()->route('sales.index');
        }

        $today = Carbon::today();

        $revenueToday = Sale::whereDate('created_at', $today)->where('status', 'COMPLETADA')->sum('total');

        $salesCountToday = Sale::whereDate('created_at', $today)->where('status', 'COMPLETADA')->count();

        $availableStock = Unit::where('status', 'disponible')->count();

        // 4. El Top 5 de Productos Más Vendidos
        $topProducts = DB::table('sale_details')
            ->join('units', 'sale_details.unit_id', '=', 'units.id')
            ->join('products', 'units.product_id', '=', 'products.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id') // Unimos con sales para ver el status
            ->where('sales.status', 'COMPLETADA') // Solo contamos ventas reales, no anuladas
            ->select('products.name', DB::raw('COUNT(sale_details.id) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();


        $topClients = DB::table('sales')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->where('sales.status', 'COMPLETADA')
            ->select('clients.name', DB::raw('SUM(sales.total) as total_sold'))
            ->groupBy('clients.id', 'clients.name')
            ->orderBy('total_sold', 'desc')
            ->limit(3)
            ->get();

        $recentSales = Sale::with('client')->latest()->limit(5)->get();

        // Obtenemos las ventas de los últimos 7 días agrupadas por fecha

        
        $salesData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->where('status', 'COMPLETADA')
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'asc')
        ->get();

        $labels = $salesData->pluck('date');
        $totals = $salesData->pluck('total');
        

        return view('dashboard', compact('revenueToday', 'salesCountToday', 'availableStock', 'topProducts', 'topClients', 'recentSales', 'labels', 'totals'));

    }   
}
