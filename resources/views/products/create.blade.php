@extends('layouts.app') 

@section('content')
    <h1>Crear Productos</h1>
    <form method = "POST" action="{{route('products.store')}}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Imagen del producto:</label>
            <input type="file" name = "image" accept="image/*">
        </div>

        <div>
            <label>Nombre:</label>
            <input type="text" name = "name">
            @error('name')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>
        <div>
            <label>Precio de venta:</label>
            <input type="number" name="sale_price">
            @error('sale_price')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>
        <div>
            <label>Categoria:</label>
            <select name="category_id">
                @foreach($categories as $category)
                    <option value = "{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>

        <button type="submit">Guardar</button>
    </form>
@endsection