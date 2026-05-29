<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('GET /api/v1/products devuelve lista con estructura correcta', function () {


    $products = Product::factory()->count(3)->create();

    // 2. Creamos un usuario cualquiera
    $user = User::factory()->create();

    // 3. Le decimos a Sanctum: "Dale un pase VIP automático a este usuario para la API"
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/products');

    $response->assertStatus(200)->assertJsonStructure([
        'success',
        'data',
        'meta' => [
            'current_page',
            'per_page',
            'total'
        ],
    ]);
});