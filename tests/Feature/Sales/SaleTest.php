<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Client;
use App\Models\SaleDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);


test('a user can create a sale and mark unit as sold', function () {

    // Crea un usuario
    $user = User::factory()->create();

    //Le da acceso a la ruta

    Sanctum::actingAs($user);

    // Creamos Cliente

    $client = Client::factory()->create();

    // Creamos un producto
    $product = Product::factory()->create();

    // Creamos una unidad
    $unit = Unit::factory()->create([
        'product_id' => $product->id,
    ]);


    $response = $this->post('/sales', [
        'prices' => [$product->sale_price],
        'unit_ids' => [$unit->id],
        'voucher' => 'boleta',
        'client_id' => $client->id,
    ]);

    $response->assertRedirectToRoute('sales.index');
    $unit->refresh();
    expect($unit->status)->toBe('vendido');
    $saleDetail = SaleDetail::where('unit_id','=', $unit->id)->exists();
    expect($saleDetail)->toBeTrue();
});
