@extends('layouts.app') 

@section('content')
    <h1>Productos</h1>

    @if(session('message'))
        <div>
            <p style = "color:red">{{session('message')}}</p>
        </div>
    @endif

    <div>
        <form method = "GET"  action = "{{route('products.index')}}">
            <label>Búsqueda:</label>
            <input type="text" name = "busqueda">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <a href="{{route('products.create')}}">Crear Producto</a>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Precio de venta</th>
                <th>Categoria</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{asset('storage/' .$product->image)}}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" >
                        @else
                            <img src="{{asset('img/no-image-png')}}" style = "width: 50px;">    
                        @endif
                    </td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->sale_price}}</td>
                    <td>{{$product->category->name}}</td>
                    <td><a href="{{route('products.edit', $product)}}">Editar</a>
                    <td>
                        <form method = "POST" action="{{route('products.destroy', $product)}}">
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