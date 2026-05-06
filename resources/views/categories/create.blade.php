@extends('layouts.app') 

@section('content')
    <h1>Crear Categoria</h1>

    <form method = "POST" action = "{{route('categories.store')}}">
        @csrf
        <div>
            <label>Nombre:</label>
            <input type = "text" name = "name">
            <button type = "submit">Guardar</button>
            @error('name')<span style = "color:red">{{$message}}</span>@enderror

        </div>
    </form>
@endsection