@extends('layouts.app') 

@section('content')
    <h1>Editar Categoria</h1>

    <form method = "POST" action = "{{route('categories.update', $category->id)}}">
        @csrf
        @method('PUT')
        <div>
            <label>Nombre:</label>
            <input type = "text" name = "name" value = "{{$category->name}}">
            <button type = "submit">Guardar</button>
            @error('name')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>
    </form>
@endsection