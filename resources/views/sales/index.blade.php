@extends('layouts.app') 

@section('content')
    <h1>Ventas</h1>

    @if(session('message'))
        <div>
            <p style = "color:red">{{session('message')}}</p>
        </div>
    @endif

    <div>
        <form method = "GET"  action = "{{route('sales.index')}}">
            <label>Búsqueda:</label>
            <input type="text" name = "busqueda">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <a href="{{route('sales.create')}}">Crear ventas</a>

    <a href="{{ route('sales.export') }}" class="btn btn-success"> Descargar Excel</a>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Voucher</th>
                <th>N° voucher</th>
                <th>Fecha</th>
                <th>Subtotal</th>
                <th>IGV</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{$sale->client->name}}</td>
                    <td>{{$sale->voucher}}</td>
                    <td>{{$sale->number}}</td>
                    <td>{{$sale->date}}</td>
                    <td>{{$sale->subtotal}}</td>
                    <td>{{$sale->igv}}</td>
                    <td>{{$sale->total}}</td>
                    <td>{{$sale->status}}</td>
                    <td><a href="{{route('sales.show', $sale)}}">Ver detalles</a>
                    <td>
                        @if(Auth::user()->role === 'admin')
                            <form method="POST" action="{{route('sales.destroy', $sale)}}" class="d-inline" onsubmit="return confirm('¿Estas seguro que deseas anular esta venta? Esta acción devolverá las laptops al inventario y no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Anular</button>
                            </form>
                        @endif    
                    </td>
                    <td><a href="{{route('sales.pdf', $sale->id)}}">PDF</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class = "d-flex justify-content-center mt-4">
        {{$sales->links('pagination::bootstrap-5')}}
    </div>

@endsection