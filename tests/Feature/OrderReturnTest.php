<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderReturn;
use Tests\TestCase;

class OrderReturnTest extends TestCase
{
    /**
     * A basic feature test example.
     */
   
        use RefreshDatabase;

    public function test_order_return_create(): void
    {
       
        $user = User::factory()->create();

        $this->actingAs($user);

       
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        
        $orderReturn = OrderReturn::factory()->create([
            'order_id' => $order->order_id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id, 
            'user_id' => $user->id,
        ]);
        
        $this->assertDatabaseHas('order_returns', [
            'order_id' => $order->order_id,
            'user_id' => $user->id,
        ]);

    }

    public function test_order_return_update(): void
    {
        
        $user = User::factory()->create();

        
        $this->actingAs($user);

        
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        
        $orderReturn = OrderReturn::factory()->create([
            'order_id' => $order->order_id,
            'user_id' => $user->id,
        ]);

        
        $updatedData = [
            'status' => 'aprobado',
            'reason' => 'Producto defectuoso',
            'admin_notes' => 'Revisión completada',
        ];

        $orderReturn->update($updatedData);

  
        $this->assertDatabaseHas('order_returns', [
            'order_id' => $order->order_id,
            'user_id' => $user->id,
            'status' => 'aprobado',
            'reason' => 'Producto defectuoso',
            'admin_notes' => 'Revisión completada',
        ]);
    }
    
}
