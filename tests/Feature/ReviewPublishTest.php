<?php

namespace Tests\Feature;

use App\Models\category;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ReviewPublishTest extends TestCase
{

    /** @test */
    public function a_user_can_publish_a_review_with_authentication(): void
    {

    // Crear un category
    $category = Category::factory()->create();

    // Crea un product y asignar el category_id
    $product = Product::factory()->create([
        'category_id' => $category->id, 
    ]); 
        // Crear un usuario y simular su autenticación
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post("/api/publishReview/{$product->id}", [
            'user_id' => $user->id,
            'calification' => 4,
            'comment' => 'Great product!',
        ]);

        // Verificar que la respuesta
        $response->assertStatus(201);
    
        // Verificar que la reseña se haya creado en la base de datos
        $this->assertDatabaseHas('reviews', [
            'user_id' =>  $user->id,
            'product_id' => $product->id,
            'calification' => 4,
            'comment' => 'Great product!',
            'review_date' => now()->toDateTimeString(),
        ]);    
    }
    
    
}
