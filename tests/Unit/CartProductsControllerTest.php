<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\CartProduct;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;


class CartProductsControllerTest extends TestCase
{

    use RefreshDatabase;
    
    protected function setUp(): void
{
    parent::setUp();

    // Inserciones necesarias
    $sellerId = DB::table('users')->insertGetId([
        'name' => 'Vendedor',
        'email' => 'vendedor@example.com',
        'password' => Hash::make('12345678'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $customerId = DB::table('users')->insert([
        'name' => 'Pruebin',
        'email' => 'prueba@gmail.com',
        'password' => Hash::make('12345678'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('categories')->insert([
        ['id' => null, 'name' => 'Electronics']
    ]);


    DB::table('stores')->insert([
        'seller_id' => $sellerId,
        'store_name' => 'TechWorld Store',
        'description' => 'A store that sells high-quality electronics and gadgets.',
        'email' => 'techworld@example.com',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    DB::table('role_user')->insert([
        'role_id' => 3,
        'user_id' => $sellerId,
    ]);

    DB::table('role_user')->insert([
        'role_id' => 2,
        'user_id' => $customerId,
    ]);



    DB::table('products')->insert([
        [
            'name' => 'Laptop Thunderbolt 15',
            'description' => 'Laptop with retina display and 16GB RAM, perfect for gaming.',
            'price' => 499000,
            'stock' => 30,
            'category_id' => 1,
            'id_store' => 1,
            'variation' => json_encode([['type' => 'Configuration', 'options' => ['16GB RAM / 512GB SSD', '32GB RAM / 1TB SSD']]]),
        ]]);
}

public function test_show_cart()
{
    $user = User::first(); // Usuario 'Pruebin'
    $response = $this->postJson('/api/cart/add', [
        'idproduct' => 1,
        'quantity' => 2
    ]);

    $responseCart = $this->actingAs($user)
    ->getJson('/api/cart/');

$responseCart->assertStatus(200)
->assertJsonStructure([
    'cart_products' => [
        '*' => [
            'product_name',
            'product_price',
            'product_description',
            'stock',
            'discount',
            'total',
            'discount_amount',
            'total_with_discount',
            'product_image',
        ]
    ],
    'total_amount',
    'quantityofproductsincart'
]);
}

    public function test_update_units_add()
     {


         $response = $this->postJson('/api/cart/update-units', [
             'idproduct' => 1,
             'quantity' => 4,
             'action' => 'add'
         ]);

         $response->assertStatus(200)
                  ->assertJson(['message' => 'Cantidad actualizada en el carrito.']);
    }

    // public function test_update_units_remove()
    // {
    //     $user = User::find(2); // Usuario 'Pruebin'
    //     $product = Product::factory()->create(['stock' => 10]);

    //     CartProduct::create([
    //         'user_id' => $user->id,
    //         'product_id' => $product->id,
    //         'quantity' => 5,
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->postJson('/api/cart/update-units', [
    //         'idproducttoupdate' => $product->id,
    //         'quantity' => 2,
    //         'action' => 'remove'
    //     ]);

    //     $response->assertStatus(200)
    //              ->assertJson(['message' => 'Cantidad actualizada en el carrito.']);
    // }

    // public function test_add_to_cart()
    // {
    //     $user = User::find(2); // Usuario 'Pruebin'
    //     $product = Product::factory()->create(['stock' => 10]);

    //     $this->actingAs($user);

    //     $response = $this->postJson('/api/cart/add', [
    //         'idproduct' => $product->id,
    //         'quantity' => 2
    //     ]);

    //     $response->assertStatus(201)
    //              ->assertJson(['message' => 'El producto se ha agregado al carrito.']);
    // }

    // public function test_remove_product_units()
    // {
    //     $user = User::find(2); // Usuario 'Pruebin'
    //     $product = Product::factory()->create(['stock' => 10]);

    //     CartProduct::create([
    //         'user_id' => $user->id,
    //         'product_id' => $product->id,
    //         'quantity' => 5,
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->postJson('/api/cart/remove-units', [
    //         'idproduct' => $product->id,
    //         'quantity' => 2
    //     ]);

    //     $response->assertStatus(200)
    //              ->assertJson(['message' => 'Cantidad actualizada en el carrito.']);
    // }

    // public function test_remove_product_from_cart()
    // {
    //     $user = User::find(2); // Usuario 'Pruebin'
    //     $product = Product::factory()->create(['stock' => 10]);

    //     CartProduct::create([
    //         'user_id' => $user->id,
    //         'product_id' => $product->id,
    //         'quantity' => 5,
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->postJson('/api/cart/remove', [
    //         'idproducttoremove' => $product->id
    //     ]);

    //     $response->assertStatus(200)
    //              ->assertJson(['message' => 'El producto se ha eliminado del carrito.']);
    // }

    // public function test_remove_all_products_from_cart()
    // {
    //     $user = User::find(2); // Usuario 'Pruebin'
    //     $product1 = Product::factory()->create(['stock' => 10]);
    //     $product2 = Product::factory()->create(['stock' => 20]);

    //     CartProduct::create([
    //         'user_id' => $user->id,
    //         'product_id' => $product1->id,
    //         'quantity' => 2,
    //     ]);

    //     CartProduct::create([
    //         'user_id' => $user->id,
    //         'product_id' => $product2->id,
    //         'quantity' => 3,
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->postJson('/api/cart/remove-all');

    //     $response->assertStatus(200)
    //              ->assertJson(['message' => 'Los productos se han eliminado del carrito.']);
    // }
}