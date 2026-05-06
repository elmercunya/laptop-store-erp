<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante {{ $sale->number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Mi Empresa ERP</h2>
        <p>Comprobante de Venta: <strong>{{ $sale->number }}</strong></p>
        <p>Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="details">
        <p><strong>Cliente:</strong> {{ $sale->client->name }}</p>
        </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Número de Serie</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleDetails as $detail)
                <tr>
                    <td>{{ $detail->unit->product->name }}</td>
                    <td>{{ $detail->unit->serial_number }}</td>
                    <td>S/ {{ number_format($detail->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total a Pagar: S/ {{ number_format($sale->total, 2) }}
    </div>

</body>
</html>