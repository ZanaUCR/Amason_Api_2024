<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;


class ReviewDeleteTest extends TestCase
{
/** @test */
 public function a_user_can_delete_their_review()
 {

     $user = User::find(7);
     $this->actingAs($user);
     $review_id = 5;
     /*
     $product = Product::factory()->create();
     $review = Review::factory()->create([
         'user_id' => $user->id,
         'product_id' => $product->id,
         'calification' => 5,
         'comment' => 'Great product!',
     ]);
     */

     $response = $this->delete("/api/reviews/deleteReview/{$review_id}" , [
         'review_id' => $review_id, 
     ]);

     $response->assertStatus(200)
              ->assertJson(['message' => 'Review deleted']);
     $this->assertDatabaseMissing('reviews', [
         'id' => $review_id,
     ]);
 }

}
