<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    //Basado en carrito
    public function getRecommendations(Request $request)
    {
        return response()->json(['message' => 'API funcionando correctamente']);
    }
}
