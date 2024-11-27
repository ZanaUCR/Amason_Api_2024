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
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{


    public function getRecommendationByCart(Request $request)
    {
        $userId = $request->user()->id;

        $cartProducts = cart_products::where('user_id', $userId)->pluck('product_id');

        $categories = Product::whereIn('product_id', $cartProducts)->pluck('category_id')->unique();

    // Obtener productos recomendados en las mismas categorías pero que no están en el carrito
        $recommendedProducts = Product::whereIn('category_id', $categories)
        ->whereNotIn('product_id', $cartProducts)
            ->with('images') // Supongamos que los productos tienen imágenes relacionadas
            ->get();

    return response()->json($recommendedProducts, 200);
    }


    public function getRecommendationByDiscount(Request $request)
    {
        // Consultar productos con descuento mayor a 0 e incluir las imágenes relacionadas
    $productos = Product::where('discount', '>', 0)
    ->with('images') // Incluir las imágenes relacionadas
    ->orderBy('discount', 'desc') // Opcional: Ordenar por mayor descuento
    ->get();

    // Formatear la respuesta en JSON
    return response()->json($productos, 200);

    }



public function getRecommendationByHistory($categoryId)
{
    // $user =  ;// Obtiene el usuario autenticado
    $user =  Auth::user();// Obtiene el usuario autenticado
    // $user = User::find(10); // O el ID del usuario que quieras

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
            'discount' => $product->discount, // Asegúrate de que 'discount' esté en tu modelo
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

public function getTendingProducts( )
{
        // Subconsulta para calcular el total_quantity
    $subquery = DB::table('order_items')
    ->select('product_id', DB::raw('SUM(quantity) AS total_quantity'))
    ->groupBy('product_id')
    ->orderByDesc('total_quantity');

    // Consulta principal
    $results = DB::table(DB::raw("({$subquery->toSql()}) as b"))
    ->mergeBindings($subquery) // Vincula las variables del subquery
    ->join('products as v', 'b.product_id', '=', 'v.product_id')
    ->join('product_images as pi', 'b.product_id', '=', 'pi.product_id')
    ->select('b.product_id', 'b.total_quantity', 'v.*', 'pi.image_path')
    ->orderByDesc('b.total_quantity')
    ->limit(20)
    ->get();

    return response()->json($results);

}





    
  




    
}
