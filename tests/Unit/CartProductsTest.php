<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Store;
use App\Models\cart_products;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartProductsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_product_to_cart()
    {
        // Crear un usuario y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear una tienda y un producto
        $store = Store::factory()->create();
        $product = Product::factory()->create([
            'id_store' => $store->id
        ]);

        // Verificar que el producto se haya creado correctamente
        $this->assertNotNull($product->product_id);

        // Hacer una solicitud POST al método addToCart del controlador CartProductsController
        $response = $this->postJson('/api/cart/add', [
            'idproduct' => $product->product_id,
            'quantity' => 2
        ]);

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(201);

        // Verificar que el producto se haya agregado correctamente al carrito
        $this->assertDatabaseHas('cart_products', [
            'user_id' => $user->id,
            'product_id' => $product->product_id,
            'quantity' => 2
        ]);
    }

    /** @test */
    public function it_can_remove_product_from_cart()
    {
        // Crear un usuario y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear una tienda y un producto
        $store = Store::factory()->create();
        $product = Product::factory()->create([
            'id_store' => $store->id
        ]);

        // Agregar el producto al carrito
        $cartProduct = cart_products::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->product_id,
            'quantity' => 2
        ]);

        // Hacer una solicitud POST al método removeProductFromCart del controlador CartProductsController
        $response = $this->postJson('/api/cart/remove-product', [
            'idproducttoremove' => $product->product_id
        ]);

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que el producto se haya eliminado correctamente del carrito
        $this->assertDatabaseMissing('cart_products', [
            'user_id' => $user->id,
            'product_id' => $product->product_id
        ]);
    }

    /** @test */
    public function it_can_remove_all_products_from_cart()
    {
        // Crear un usuario y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear una tienda y productos
        $store = Store::factory()->create();
        $product1 = Product::factory()->create([
            'id_store' => $store->id
        ]);
        $product2 = Product::factory()->create([
            'id_store' => $store->id
        ]);

        // Agregar los productos al carrito
        cart_products::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product1->product_id,
            'quantity' => 2
        ]);
        cart_products::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product2->product_id,
            'quantity' => 3
        ]);

        // Hacer una solicitud POST al método removeAllProductsFromCart del controlador CartProductsController
        $response = $this->postJson('/api/cart/removeall');

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que todos los productos se hayan eliminado correctamente del carrito
        $this->assertDatabaseMissing('cart_products', [
            'user_id' => $user->id
        ]);
    }
}
