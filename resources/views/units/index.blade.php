@extends('layouts.app') 

@section('content')
    <h1>Unidades de los Productos</h1>

    @if(session('message'))
        <div>
            <p style = "color:red">{{session('message')}}</p>
        </div>
    @endif

    <div>
        <form method = "GET"  action = "{{route('units.index')}}">
            <label>Búsqueda:</label>
            <input type="text" name = "busqueda">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <a href="{{route('units.create')}}">Crear Unidades</a>

    

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Numero de serie</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units as $unit)
                <tr>
                    <td>{{$unit->product->name}}</td>
                    <td>{{$unit->serial_number}}</td>
                    <td>{{$unit->status}}</td>
                    <td><a href="{{route('units.edit', $unit)}}">Editar</a>
                    <td>
                        <form method = "POST" action="{{route('units.destroy', $unit)}}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection