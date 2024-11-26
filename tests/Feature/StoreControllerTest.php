<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Store;
use App\Models\product;
use App\Models\User;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_data_when_creating_a_store()
    {
        $response = $this->postJson('api/store', [
            // Datos incompletos
            'store_name' => 'My Store'
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message', 'errors', 'status']);
    }

    /** @test */
    public function it_creates_a_store_with_valid_data()
    {
       
        $user = User::factory()->create();

        $response = $this->postJson('api/store', [
            'seller_id' => $user->id, 
            'store_name' => 'My Store',
            'description' => 'This is my store',
            'email' => 'store@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['vendedor', 'status']);

        $this->assertDatabaseHas('stores', [
            'store_name' => 'My Store',
            'email' => 'store@example.com',
            'seller_id' => $user->id,
        ]);
    }
    /** @test */
    public function it_fetches_stores_by_seller_id()
    {
       
        $user = User::factory()->create();

       
        $store = Store::factory()->create([
            'seller_id' => $user->id,
        ]);

        
        $response = $this->getJson('api/store/' . $user->id);

       
        $response->assertStatus(200)
            ->assertJson([
                [
                    'Id' => $store->id,
                    'store_name' => $store->store_name,
                    'description' => $store->description,
                    'email' => $store->email,
                ]
            ]);
    }
    /** @test */
    public function it_fetches_store_by_id()
    {
        $store = Store::factory()->create();

        $response = $this->getJson("api/store/id/{$store->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $store->id,
                'store_name' => $store->store_name,
            ]);
    }
    /** @test */
    public function it_deletes_a_store_and_associated_products()
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create(['id_store' => $store->id]);

        $response = $this->deleteJson("api/store/{$store->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Tienda eliminada con éxito']);

        $this->assertDatabaseMissing('stores', ['id' => $store->id]);
        $this->assertDatabaseMissing('products', ['product_id' => $product->id]);
    }
    /** @test */
    public function it_updates_a_store()
    {
        $store = Store::factory()->create([
            'store_name' => 'Old Store',
            'description' => 'Old description',
            'email' => 'old@example.com',
        ]);

        $response = $this->patchJson("api/store/{$store->id}", [
            'store_name' => 'New Store',
            'description' => 'New description',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Tienda actualizada con éxito',
                'store' => [
                    'store_name' => 'New Store',
                    'description' => 'New description',
                    'email' => 'old@example.com', // No actualizado
                ],
            ]);

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'store_name' => 'New Store',
            'description' => 'New description',
        ]);
    }

}
