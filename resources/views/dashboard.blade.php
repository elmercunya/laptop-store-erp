@extends('layouts.app') 

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Ingresos de Hoy</div>
                <div class="card-body">
                    <h2 class="card-title">S/ {{ number_format($revenueToday, 2) }}</h2>
                    <p class="card-text">Total de ventas completadas hoy.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Ventas Realizadas</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $salesCountToday }} comprobantes</h2>
                    <p class="card-text">Operaciones registradas hoy.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Stock Disponible</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $availableStock }} Laptops</h2>
                    <p class="card-text">Unidades listas para la venta.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm" style="max-width: 800px; margin: auto;">
        <div class="card-body">
            <h4 class="card-title">Ventas de los últimos 7 días</h4>
            <canvas id="ventasChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="row mt-4">
        <div class="col-md-8 offset-md-2"> <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Top 5 Laptops Más Vendidas</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Modelo de Laptop</th>
                                <th class="text-center">Unidades Vendidas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $product->total_sold }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Aún no hay ventas registradas para generar el ranking.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8 offset-md-2"> <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Top 3 Clientes que más gastaron</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th class="text-center">Total gastado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topClients as $index => $client)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">
                                            S/ {{ number_format($client->total_sold, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Aún no hay clientes registrados para generar el ranking.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Últimas Operaciones</h5>
        </div>
        <ul class="list-group list-group-flush">
            @foreach($recentSales as $sale)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $sale->number }}</strong> - {{ $sale->client->name }}
                        <br>
                        <small class="text-muted">{{ $sale->created_at->diffForHumans() }}</small>
                    </div>
                    <span class="badge bg-success font-weight-bold">
                        S/ {{ number_format($sale->total, 2) }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //Agarramos el canva
            const ctx = document.getElementById('ventasChart').getContext('2d');

            //recibimos los datos en php y los convertimos a json

            const labels = @json($labels);
            const dataTotals = @json($totals);

            // Configuramos e iniciamos el grafico

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets : [{
                        label: 'Total vendido (S/)',
                        data: dataTotals,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54,162,235,1)',
                        borderWith: 1,
                        borderRadius: 5
                    }] // Cierre del datasets
                }, // Cierre del data
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true // Obligamos a que el eje Y empiece en 0
                        }
                    }
                }
            }) // Cierre de creacion de chart
        }) // Cierre de evento  
    </script>
@endsection
    