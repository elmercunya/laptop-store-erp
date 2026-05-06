@extends('layouts.app') 

@section('content')
    <h1>Editar Unidades</h1>
    <form method = "POST" action="{{route('units.update',$unit)}}">
        @csrf
        @method('PUT')
        <div>
            <label>Producto:</label>
            <select name="product_id">
                @foreach($products as $product)
                    <option value = "{{$product->id}}" @if($unit->product_id == $product->id) selected @endif>{{$product->name}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Numero de serie:</label>
            <input type="text" name="serial_number" value = "{{$unit->serial_number}}">
            @error('serial_number')
                <span style = "color:red">{{$message}}</span>
            @enderror
        </div>

        <button type="submit">Guardar</button>
    </form>
@endsection