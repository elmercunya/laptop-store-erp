@extends('layouts.app') 

@section('content')
    <h1>Crear Clientes</h1>
    <form method = "POST" action="{{route('clients.store')}}">
        @csrf
        <div>
            <label>Tipo de documento:</label>
            <select name="document_type">
                @foreach($types as $type)
                    <option value = "{{$type}}">{{$type}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Numero de documento:</label>
            <input type="text" name="document_number">
            @error('document_number')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>
        <div>
            <label>Nombre:</label>
            <input type="text" name="name">
            @error('name')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>

        <button type="submit">Guardar</button>
    </form>
@endsection