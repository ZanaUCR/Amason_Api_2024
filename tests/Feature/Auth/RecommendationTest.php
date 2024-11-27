<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Product;
use Tests\TestCase;




class RecommendationTest extends TestCase

{
    use RefreshDatabase;
    public function test_get_recommendation_by_discount(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // Crear productos con y sin descuento
        $productWithDiscount = Product::factory()->create([
            'discount' => 20,
            'id_store' => 1, // Add this line
        ]);

        $productWithoutDiscount = Product::factory()->create([
            'discount' => 0,
            'id_store' => 1, // Add this line
        ]);

        // Hacer la solicitud al método getRecommendationByDiscount
        $response = $this->getJson('/api/recommendations/discount');

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que el producto con descuento esté en la respuesta
        $response->assertJsonFragment([
            'id' => $productWithDiscount->id,
            'discount' => 20,
        ]);

        // Verificar que el producto sin descuento no esté en la respuesta
        $response->assertJsonMissing([
            'product_id' => $productWithoutDiscount->product_id,
        ]);
    }
}