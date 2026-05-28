<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('GET /api/v1/products devuelve lista con estructura correcta', function () {


    $products = Product::factory()->count(3)->create();

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