<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    // Test that processes the order and finishes it successfully
    public function test_process_order_successful()
    {
        // Create user and cart products
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        $this->actingAs($user);

        // Create cart product (simulate product in cart)
        $cartProduct = $user->cartProducts()->create([
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Simulate processing the order
        $response = $this->postJson('/api/order/process', [
            'paymentMethod' => 'card',
            'cardNumber' => '4111111111111111',
        ]);

        // Assert response
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);
    }

    // Test that processes the order and fails due to invalid card number
    public function test_process_order_invalid_card()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/order/process', [
            'paymentMethod' => 'card',
            'cardNumber' => '1234567890123456',
        ]);

        // Assert failure due to invalid card
        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'failed',
            'message' => 'Invalid card number.'
        ]);
    }
    public function test_create_order_successful()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    // Add products to cart
    $product = Product::factory()->create();
    $user->cartProducts()->create([
        'product_id' => $product->id,
        'quantity' => 2
    ]);

    // Create order request
    $response = $this->postJson('/api/order/create', [
        'user_id' => $user->id,
        'status' => 1
    ]);

    // Assert that the order is created
    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
        'order_id' => true, // Validate that the order ID is returned
    ]);

    // Assert that the order was saved in the database
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'status' => 1,
    ]);

    // Assert that the order items were saved
    $this->assertDatabaseHas('order_items', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
}
public function test_finish_order_successful()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create a pending order
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => 1 // Pending order
    ]);

    // Call the finish order method
    $response = $this->postJson('/api/order/finish', [
        'order_id' => $order->id,
        'payment_method_id' => 1, // Assume payment method ID is 1 for card
    ]);

    // Assert the order status is updated
    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
        'message' => 'Order finished.',
    ]);

    // Assert that the order status is now 'finished'
    $this->assertDatabaseHas('orders', [
        'order_id' => $order->id,
        'status' => 2, // Finished order
    ]);
}

public function test_finish_order_not_found()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    // Try to finish an order that does not exist
    $response = $this->postJson('/api/order/finish', [
        'order_id' => 9999, // Non-existent order ID
        'payment_method_id' => 1,
    ]);

    // Assert that the order was not found
    $response->assertStatus(404);
    $response->assertJson([
        'status' => 'failed',
        'message' => 'Order not found.',
    ]);
}
public function test_validate_card_number_success()
{
    $paymentMethodController = $this->createMock(PaymentMethodController::class);
    $paymentMethodController->method('validateCardNumber')
                            ->willReturn(true);

    $cardNumber = '4111111111111111';
    $response = $this->postJson('/api/order/validate-card', [
        'cardNumber' => $cardNumber
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'success']);
}

public function test_validate_card_number_failure()
{
    $paymentMethodController = $this->createMock(PaymentMethodController::class);
    $paymentMethodController->method('validateCardNumber')
                            ->willReturn(false);

    $cardNumber = '1234567890123456';
    $response = $this->postJson('/api/order/validate-card', [
        'cardNumber' => $cardNumber
    ]);

    $response->assertStatus(400);
    $response->assertJson(['status' => 'failed', 'message' => 'Invalid card number.']);
}

}
