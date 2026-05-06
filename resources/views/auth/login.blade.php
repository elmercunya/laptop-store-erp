<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Mi ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { height: 100vh; display: flex; align-items: center; background-color: #f8f9fa; }
        .login-form { width: 100%; max-width: 400px; padding: 15px; margin: auto; }
    </style>
</head>
<body>
    <main class="login-form">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Acceso al Sistema</h3>
                
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" name="user" class="form-control @error('user') is-invalid @enderror" value="{{ old('user') }}" required autofocus>
                        @error('user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>