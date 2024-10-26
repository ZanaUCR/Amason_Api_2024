<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        "category_id" => \App\Models\Category::factory(),
        "name" => $this->faker->word,
        "description" => $this->faker->text,
        "price" => $this->faker->randomFloat(2, 0, 9999),
        "stock" => $this->faker->numberBetween(1, 100),
        ];
    }
}
