<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     * Prueba para almacenar un ticket.
     */
    public function test_store_ticket()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Simular autenticación

        $data = [
            'order_package' => 'Test Order',
            'claim_type' => 'Test Claim',
            'subject' => 'Test Ticket',
            'description' => 'This is a test description',
            'file' => null,
            'notify_by' => 'email',
        ];

        $response = $this->postJson('/api/tickets/store', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['subject' => 'Test Ticket']);
    }

    /**
     * 
     * Prueba para listar todos los tickets.
     */
    public function test_list_tickets()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Simular autenticación

        Ticket::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tickets');

        $response->assertStatus(200)
            ->assertJsonCount(3); // Verifica que existen 3 tickets en la respuesta
    }

    /**
     * 
     * Prueba para mostrar un ticket específico.
     */
    public function test_show_ticket()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Simular autenticación

        $ticket = Ticket::factory()->create([
            'user_id' => $user->id,
            'subject' => 'Ticket for testing show'
        ]);

        $response = $this->getJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['subject' => 'Ticket for testing show']);
    }
}