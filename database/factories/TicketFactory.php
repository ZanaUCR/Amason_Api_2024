<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'order_package' => $this->faker->word,
            'claim_type' => $this->faker->word,
            'subject' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'file' => null,
            'notify_by' => 'email',
            'user_id' => \App\Models\User::factory(),
        ];
    }
}