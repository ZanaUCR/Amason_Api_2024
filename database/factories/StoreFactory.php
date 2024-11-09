<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seller_id' => User::factory(),  // Crea un usuario de prueba y asigna su id como seller_id
            'location_id' => $this->faker->optional()->randomNumber(),  // Genera un número aleatorio o nulo
            'store_name' => $this->faker->company(),  // Nombre de la tienda
            'description' => $this->faker->optional()->paragraph(),  // Descripción opcional
            'email' => $this->faker->unique()->safeEmail(),  // Email único de la tienda
            'logo' => $this->faker->optional()->imageUrl(200, 200, 'business', true, 'logo'),  // URL de logo opcional
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
