<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->sentence(3),
            'sale_price' => fake()->numberBetween(1000, 5000),
            'image' => 'products/default.png',
        ];
    }
}