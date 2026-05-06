@extends('layouts.app') 

@section('content')
    <h1>Editar Producto</h1>

    <form method = "POST" action="{{route('products.update', $product)}}" enctype = "multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label>Imagen del producto:</label>
            <input type="file" name="image" accept = "image/*">
        </div>
        <div>
            <label>Nombre:</label>
            <input type="text" value = "{{$product->name}}" name = "name">
            @error('name')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>
        <div>
            <label>Precio de Venta:</label>
            <input type="text" value = "{{$product->sale_price}}" name = "sale_price">
            @error('sale_price')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>
        <div>
            <label>Categoria:</label>
            <select name="category_id">
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($product->category_id == $category->id) selected @endif >{{$category->name}}</option>
                @endforeach
            </select>
        </div>

        <button type="submit">Guardar</button>
    </form>
@endsection