<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\cart_products;

class RecommendationController extends Controller
{


    public function getRecommendationByCart(Request $request)
    {
        $userId = $request->user()->id;
    
        // Llamamos al método en CartProducts para obtener los IDs de productos en el carrito
        $cartProducts = cart_products::getUserCartProductIds($userId);
    
        // Llamamos al método en Product para obtener las categorías de los productos en el carrito
        $categories = Product::getCategoriesByProductIds($cartProducts);
    
        // Llamamos al método en Product para obtener los productos recomendados
        $recommendedProducts = Product::getRecommendedProducts($categories, $cartProducts);
    
        return response()->json($recommendedProducts, 200);
    }
    




public function getRecommendationByHistory($categoryId)
{
    // $user =  ;// Obtiene el usuario autenticado
    $user =  Auth::user()// Obtiene el usuario autenticado

    // Obtener los productos comprados en la categoría especificada
    $purchasedProducts = $user->getPurchasedProductsInCategory($categoryId);

    // Obtener todos los productos en la categoría especificada
    $allProductsInCategory = Product::getAllProductsInCategory($categoryId);

    $combinedProducts = $purchasedProducts->concat($allProductsInCategory)
    ->unique('product_id')
    ->values();

    // Mapear los productos para incluir el image_path
    $result = $combinedProducts->map(function ($product) {
        return [
            'product_id' => $product->product_id, // Ajusta según el nombre de tu ID
            'name' => $product->name, // Ajusta si necesitas más atributos
            'price' => $product->price, // Asegúrate de que 'price' esté en tu modelo
            'description' => $product->description, // Asegúrate de que 'description' esté en tu modelo
            'image_path' => $product->images->isNotEmpty() ? $product->images->first()->image_path : null, // Asegúrate de que no esté vacío
        ];
    });

    return response()->json($result);
}


public function testProductImages($categoryId)
{
    $user = User::find(4); // O el ID del usuario que quieras
    $purchasedProducts = $user->getPurchasedProductsInCategory($categoryId);
    $allProductsInCategory = Product::getAllProductsInCategory($categoryId);

    // Verificar si se están obteniendo imágenes
    foreach ($purchasedProducts as $product) {
        if ($product->images->isEmpty()) {
            dd("No hay imágenes para el producto ID: " . $product->id);
        }
    }

    foreach ($allProductsInCategory as $product) {
        if ($product->images->isEmpty()) {
            dd("No hay imágenes para el producto ID: " . $product->id);
        }
    }

    return response()->json([
        'purchasedProducts' => $purchasedProducts,
        'allProductsInCategory' => $allProductsInCategory,
    ]);
}







    
  




    
}
