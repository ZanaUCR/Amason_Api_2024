<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => \App\Models\User::factory(),
            "payment_method_id" => \App\Models\PaymentMethod::factory(),
            "total_amount" => $this->faker->randomFloat(2, 0, 9999),
            "status" => $this->faker->numberBetween(0, 1),
        ];
    }
}
