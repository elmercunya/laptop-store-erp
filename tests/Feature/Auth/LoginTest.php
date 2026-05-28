<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('admin con credenciales correctas inicia sesión y ve dashboard', function () {

    // Se crea el usuario de prueba para el test
    $admin = User::factory()->create([
        'user' => 'admin_test',
        'password' => bcrypt('password123'),
        'role' => 'admin'
    ]);

    //Simulacion de usuario llenando sus crendenciales y le da a iniciar sesion
    $response = $this->post('/login', [
        'user' => 'admin_test',
        'password' => 'password123'
    ]);

    // Se envia al dashboard
    $response->assertRedirect('/dashboard');

    // Verificamos que el usuario este logueado
    $this->assertAuthenticated();

});

it('usuario con contraseña incorrecta no inicia sesión', function () {
    // ARRANGE: crear usuario admin con factory (mismo que arriba)
    $admin = User::factory()->create([
        'user' => 'admin_test',
        'password' => bcrypt('password123'),
        'role' => 'admin'
    ]);

    //Simulacion de usuario llenando sus crendenciales y le da a iniciar sesion
    $response = $this->from('/login')->post('/login', [
        'user' => 'admin_test',
        'password' => 'wrong_password'
    ]);

    // Nos dirige al login
    $response->assertRedirect('/login');

    // Se usa para verificar que no haya login
    $this->assertGuest();

    // Verificar si existe errores de validación de los datos
    $response->assertSessionHasErrors(['user']);

});