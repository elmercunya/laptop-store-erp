@extends('layouts.app') 

@section('content')
    <h1>Categorias</h1>

    @if(session('message'))
        <div>
            <p style = "color:red">{{session('message')}}</p>
        </div>
    @endif

    <a href = "{{route('categories.create')}}">Crear categoria</a>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
            
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{$category->name}}</td>
                    <td><a href = "{{route('categories.edit', $category)}}">Editar</a></td>
                    <td>
                        <form method = "POST" action = "{{route('categories.destroy', $category)}}">
                            @csrf
                            @method('DELETE')
                            <button>Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection