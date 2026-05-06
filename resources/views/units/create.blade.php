@extends('layouts.app') 

@section('content')
    <h1>Crear Unidades</h1>
    <form method = "POST" action="{{route('units.store')}}">
        @csrf
        <div>
            <label>Producto:</label>
            <select name="product_id">
                @foreach($products as $product)
                    <option value = "{{$product->id}}">{{$product->name}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Numero de serie:</label>
            <input type="text" name="serial_number">
            @error('serial_number')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>

        <button type="submit">Guardar</button>
    </form>
@endsection