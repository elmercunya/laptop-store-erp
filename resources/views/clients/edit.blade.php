@extends('layouts.app') 

@section('content')
    <h1>Editar Cliente</h1>
    <form method = "POST" action="{{route('clients.update', $client)}}">
        @csrf
        @method('PUT')
        <div>
            <label>Tipo de documento:</label>
            <select name="document_type">
                @foreach($types as $type)
                    <option value = "{{$type}}" @if($type == $client->document_type) selected @endif>{{$type}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Numero de documento:</label>
            <input type="text" name="document_number" value = "{{$client->document_number}}">
            @error('document_number')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>
        <div>
            <label>Nombre:</label>
            <input type="text" name="name" value = "{{$client->name}}">
            @error('name')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>

        <button type="submit">Guardar</button>
    </form>
@endsection