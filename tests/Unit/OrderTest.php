<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_an_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Simular autenticación

        $store = Store::factory()->create();

        $paymentMethod = PaymentMethod::factory()->create([
            'id' => 1, 
            'payment_method' => 'card'
        ]);

        $product = Product::factory()->create([
            'id_store' => $store->id
        ]);

        $this->assertNotNull($product->product_id);

        $user->cartProducts()->create([
            'product_id' => $product->product_id,
            'quantity' => 2
        ]);

        $response = $this->postJson('/api/order/create', [
            'user_id' => $user->id,
            'status' => 1, 
            'payment_method_id' => $paymentMethod->id
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => $product->price * 2,
            'status' => 1,
            'payment_method_id' => $paymentMethod->id
        ]);
    }

    /** @test */
    public function finish_an_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $paymentMethod = PaymentMethod::factory()->create([
            'id' => 1, 
            'payment_method' => 'card'
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 1, 
            'payment_method_id' => $paymentMethod->id
        ]);

        $response = $this->postJson('/api/order/finish', [
            'order_id' => $order->order_id,
            'payment_method_id' => $paymentMethod->id
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'status' => 2
        ]);
    }

    /** @test */
    public function cancel_an_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user); 

        $paymentMethod = PaymentMethod::factory()->create([
            'id' => 1, 
            'payment_method' => 'card'
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 1,
            'payment_method_id' => $paymentMethod->id
        ]);

        $response = $this->postJson('/api/order/cancel', [
            'order_id' => $order->order_id,
            'user_id' => $user->id
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'status' => 3
        ]);
    }
}