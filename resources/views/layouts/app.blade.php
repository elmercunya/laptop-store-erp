<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">

            <!-- Marca / Logo -->
             
            <a class="navbar-brand">Mi Sistema ERP</a>

            <!-- Menú -->
            <ul class = "navbar-nav d-flex flex-row gap-3">
                <li class = "nav-item">
                    <a class = "nav-link text-white"href="{{route('clients.index')}}">Clientes</a>
                </li>
                
                <li class = "nav-item">
                    <a class = "nav-link text-white" href="{{route('categories.index')}}">Categorias</a>
                </li>

                <li class = "nav-item">
                    <a  class = "nav-link text-white" href="{{route('products.index')}}">Productos</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link text-white" href="{{route('units.index')}}">Unidades</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link text-white" href="{{route('sales.index')}}">Ventas</a>
                </li>

                @if(Auth::user()->role === 'admin')
                    <li class = "nav-item">
                        <a class = "nav-link text-white" href="{{route('dashboard.index')}}">Dashboard</a>
                    </li>
                @endif
            </ul>

            <div class="d-flex align-items-center">
                <span class="text-white me-3">Bienvenido, {{ Auth::user()->user }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content') </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>