<?php

namespace Database\Factories;

use App\Models\cart_products;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class cart_productsFactory extends Factory
{
    protected $model = cart_products::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'discount' => $this->faker->numberBetween(0, 100),
        ];
    }
}
