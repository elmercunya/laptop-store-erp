@extends('layouts.app') 

@section('content')
    <h1>Clientes</h1>

    @if(session('message'))
        <div>
            <p style = "color:red">{{session('message')}}</p>
        </div>
    @endif

    <div>
        <form method = "GET"  action = "{{route('clients.index')}}">
            <label>Búsqueda:</label>
            <input type="text" name = "busqueda">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <a href="{{route('clients.create')}}">Crear Cliente</a>

    <table>
        <thead>
            <tr>
                <th>Tipo de documento</th>
                <th>Numero de documento</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{$client->document_type}}</td>
                    <td>{{$client->document_number}}</td>
                    <td>{{$client->name}}</td>
                    <td><a href="{{route('clients.edit', $client)}}">Editar</a>
                    <td>
                        <form method = "POST" action="{{route('clients.destroy', $client)}}">
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