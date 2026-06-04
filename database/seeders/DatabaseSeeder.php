<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Client;
use App\Models\Unit;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (\App\Models\Product::count() > 0) {
        return;
    }


        $category = Category::create(['name' => 'Laptops']);

        $product1 = Product::create([
            'name' => 'Lenovo Ideapad 3',
            'category_id' => $category->id,
            'sale_price' => 2200,
            'image' => 'products/default.png'
        ]);

        Product::create([
            'name' => 'HP 240 G8',
            'category_id' => $category->id,
            'sale_price' => 2100,
            'image' => 'products/default.png'
        ]);

        Product::create([
            'name' => 'Dell Inspiron 15',
            'category_id' => $category->id,
            'sale_price' => 2400,
            'image' => 'products/default.png'
        ]);

        $client1 = Client::create([
            'document_type' => 'DNI',
            'document_number' => '12345678',
            'name' => 'Cliente Demo 1'
        ]);

        Client::create([
            'document_type' => 'RUC',
            'document_number' => '1087654321',
            'name' => 'Cliente Demo 2'
        ]);

        $unit = Unit::create([
            'product_id' => $product1->id,
            'serial_number' => 'PF2B34XY',
            'status' => 'disponible'
        ]);

        $sale = Sale::create([
            'client_id' => $client1->id,
            'voucher' => 'boleta',
            'number' => 'V-000001',
            'date' => Carbon::now()->toDateString(),
            'subtotal' => 2033.90 ,
            'igv' => 366.10,
            'total' => 2400,
            'status' => 'COMPLETADA'
        ]);

        SaleDetail::create([
            'sale_id' => $sale->id,
            'unit_id' => $unit->id,
            'price' => $product1->sale_price,
        ]);

        $unit->update([
            'status' => 'vendido',
        ]);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
