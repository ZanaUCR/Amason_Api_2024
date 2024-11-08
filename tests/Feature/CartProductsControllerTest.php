<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\cart_products;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartProductsControllerTest extends TestCase
{
  

    
    public function test_show_cart()
    {
        // Crear un usuario y asignarlo como vendedor
        $seller = User::factory()->create([
            'name' => 'Test Seller',
            'email' => 'testseller@example.com',
            'password' => bcrypt('password123'), // Asegúrate de establecer una contraseña válida
        ]);
    
        // Crear una tienda asociada al vendedor
        $store = Store::factory()->create([
            'seller_id' => $seller->id, // Vincular la tienda con el vendedor
            'store_name' => 'Test Store',
            'email' => 'teststore@example.com',
            'description' => 'Test Store Description',
        ]);
    
        // Crear un producto asociado a la tienda
        $product = Product::factory()->create([
            'id_store' => $store->id,
            'name' => 'Test Product',
            'price' => 100,
            'description' => 'Test Description',
            'stock' => 50,
        ]);
        
        // Crear un registro en el carrito para el usuario autenticado
       cart_products::factory()->create([
            'user_id' => $seller->id, // Utilizar el usuario autenticado
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    
        // Realizar la solicitud al método para mostrar el carrito
        $response = $this->actingAs($this->user, 'sanctum')
                         ->get(route('cart.showCart'));
    
        // Verificar la respuesta
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'cart_products' => [
                        '*' => [
                            'id', 'product_name', 'product_price', 'product_description', 'stock', 'quantity'
                        ]
                    ],
                    'total_amount',
                    'quantityofproductsincart'
                 ])
                 ->assertJson([
                    'total_amount' => 200, // Asegúrate de que este valor sea correcto según tu lógica
                    'quantityofproductsincart' => 1, // Asegúrate de que este valor sea correcto
                 ]);
    }
    

}
