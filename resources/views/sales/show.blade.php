<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Detalles</title>
</head>
<body>
    <h1>Detalle Venta: {{$sale->voucher}} {{$sale->number}}</h1>

    <button type="button" onclick = "window.print()">Imprimir</button>
    <a href="{{route('sales.pdf', $sale->id)}}">PDF</a>

    <h2>Datos del cliente:</h2>
    <h3>Nombre: {{$sale->client->name}}</h3>
    <h3>N°Documento: {{$sale->client->document_number}}</h3>

    <h2>Productos</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>N° Serie</th>
                <th>Precio</th>
            </tr>
        </thead>

        <tbody>
            @foreach($sale->saleDetails as $detail)
                <tr>
                    <td>{{$detail->unit->product->name}}</td>
                    <td>{{$detail->unit->serial_number}}</td>
                    <td>{{$detail->price}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Resumen Financiero:</h2>
    <h4>Subtotal: <span>{{$sale->subtotal}}</span></h3>
    <h4>IGV: <span>{{$sale->igv}}</span></h3>
    <h2>Total: <span>{{$sale->total}}</span></h3>


</body>
</html>