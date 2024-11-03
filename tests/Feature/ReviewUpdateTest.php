<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\category;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Tests\TestCase;

class ReviewUpdateTest extends TestCase
{


    /** @test */
    public function a_logged_in_user_can_update_their_review()
    {
/*
        // Crear un category
        $category = Category::factory()->create();

        // Crear un product y asignar el category_id
        $product = Product::factory()->create([
            'category_id' => $category->id,      ]);
*/
        //$user = User::factory()->create();
        $user = User::find(3);
        $this->actingAs($user);

        $review = Review::find(2); 

        $response = $this->put("/api/reviews/updateReview/{$review->id}", [
            'calification' => 5,
            'comment' => 'Excellent product!',
        ]);

        // Verifica que la respuesta tenga éxito
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Review updated successfully',  
                'review' => [
                    'calification' => 5,
                    'comment' => 'Excellent product!',
                ],
            ]);

        // Verifica que la reseña fue actualizada en la base de datos
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'calification' => 5,
            'comment' => 'Excellent product!',
        ]);
    }
}
