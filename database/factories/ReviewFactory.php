<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'calification' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence,
            'review_date' => now(),
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
