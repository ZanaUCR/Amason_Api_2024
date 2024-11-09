<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\Review;

class ReviewController extends Controller
{

    public function publishReview(Request $request, $product_id)
    {

        $request->validate([
            'user_id' => 'required|integer',
            'calification' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:200',
        ]);
        $user = auth()->user();

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
            'calification' => $request->calification,
            'comment' => $request->comment,
            'review_date' => Carbon::now(),
        ]);

        return response()->json($review, 201);
    }


    public function updateReview(Request $request, $review_id)
    {

        $request->validate([
            'calification' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:100',
        ]);

        $user_id = auth()->id();
        $review = Review::where('user_id', $user_id) // cuando se simula un usuario no funciona esta usando esta linea de usuario id
            ->where('id', $review_id)
            ->first();

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->update([
            'calification' => $request->input('calification'),
            'comment' => $request->input('comment'),
            'updated_at' => $request->input('updated_at'),
        ]);

        return response()->json(['message' => 'Review updated successfully', 'review' => $review], 200);
    }


    public function changeCalification(Request $request, $review_id)
    {

        $request->validate([
            'calification' => 'required|integer|min:1|max:5',
        ]);

        $user_id = auth()->id();
        $review = Review::where('user_id', $user_id)
            ->where('id', $review_id)
            ->first();

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->update([
            'calification' => $request->input('calification'),
            'updated_at' => $request->input('updated_at'),
        ]);

        return response()->json(['message' => 'Review updated successfully', 'review' => $review], 200);
    }


    public function showReviews($productId)
    {
        $listReviews = Review::where('product_id', $productId)
            ->select('id','product_id','comment', 'calification')
            ->get();
    
        return response()->json([
            'reviews' => $listReviews,
            'total_reviews' => count($listReviews)
        ]);
    }

    public function showReviewsByCalification($productId)
    {
        $listReviews = Review::where('product_id', $productId)
            ->where('calification', '>', 4) 
            ->select('id', 'product_id', 'comment', 'calification')
            ->get();
    
        return response()->json([
            'reviews' => $listReviews,
            'total_reviews' => count($listReviews)
        ]);
    }
    
    
    public function deleteReview($review_id)
    {
        $user_id = auth()->id();

        $review = Review::where('user_id', $user_id) //quitar user_id para probar
            ->where('id', $review_id)
            ->first();

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted'], 200);
    }
}
