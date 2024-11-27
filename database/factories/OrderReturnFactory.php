<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderReturn>
 */
class OrderReturnFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        
            "status" => $this->faker->randomElement(['pendiente', 'aprobado', 'rechazado', 'completado']),  
            "reason" => $this->faker->text,
            "return_date" => $this->faker->dateTimeBetween('now', '+1 month'),
            "date" => now(),
            "admin_notes" => $this->faker->text
            
        ];
    }
}
