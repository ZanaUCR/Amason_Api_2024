<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Tests\TestCase;




class RecommendationTest extends TestCase

{
    use RefreshDatabase;
    public function test_get_recommendation_by_discount(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
    
        

        $store = \App\Models\Store::factory()->create();
   

        // Crear productos con y sin descuento
        $productWithDiscount = Product::factory()->create([
            'product_id' => 1,
            'discount' => 20,
            'id_store' => $store->id, 
        ]);
        $productWithDiscount->save();

        $productWithoutDiscount = Product::factory()->create([
            'product_id' => 2,
            'discount' => 0,
            'id_store' => $store->id, 
        ]);
        $productWithoutDiscount->save();

    $this->assertDatabaseHas('products', [
        'product_id' => $productWithDiscount->product_id,
        'discount' => 20,
        'id_store' => $store->id,
    ]);

    $this->assertDatabaseHas('products', [
        'product_id' => $productWithoutDiscount->product_id,
        'discount' => 0,
        'id_store' => $store->id,
    ]);
    }
}