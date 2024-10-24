<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order_item>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           "product_id" => \App\Models\Product::factory(),
            "order_id" => \App\Models\Order::factory(),
            "quantity" => $this->faker->numberBetween(1, 10),
            "price_at_purchase" => $this->faker->randomFloat(2, 0, 9999),
        ];
    }
}
