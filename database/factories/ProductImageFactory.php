<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),  // Crea un producto de prueba y usa su ID como product_id
            'image_path' => $this->faker->imageUrl(800, 600, 'products', true, 'Image'),  // URL de imagen generada
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
