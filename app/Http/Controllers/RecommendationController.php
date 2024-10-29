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
    


    public function getProductsByCategory($categoryId)
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado
    
        // Encuentra el usuario autenticado
        $user = User::findOrFail(7);
    
        // Paso 1: Obtener productos que el usuario ya compró en la categoría específica
        $purchasedProductIds = $user->orders()
            ->whereHas('orderItems.product', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->with('orderItems.product')
            ->get()
            ->flatMap(function ($order) {
                return $order->orderItems->pluck('product_id');
            })
            ->unique(); // Lista de IDs de productos comprados, sin duplicados
    
        // Paso 2: Obtener todos los productos de la categoría
        $allCategoryProducts = Product::where('category_id', $categoryId)->get();
    
        // Paso 3: Filtrar productos que el usuario no ha comprado
        $remainingProducts = $allCategoryProducts->filter(function ($product) use ($purchasedProductIds) {
            return !$purchasedProductIds->contains($product->product_id);
        });
    
        // Paso 4: Combinar productos comprados y no comprados en un solo listado
        $combinedProducts = $allCategoryProducts->whereIn('product_id', $purchasedProductIds)->merge($remainingProducts);
    
        // Retorna la lista de productos en formato JSON
        return response()->json($combinedProducts);
    }


    public function getProductsPurchasedInCategory($categoryId)
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado
    
        // Encuentra el usuario autenticado
        $user = User::findOrFail(7);
    
        // Obtener los productos comprados por el usuario en la categoría especificada
        $purchasedProducts = $user->orders()
            ->whereHas('orderItems.product', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId); // Filtrar por la categoría
            })
            ->with(['orderItems.product' => function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId); // Filtrar también aquí para obtener solo productos de la categoría
            }])
            ->get()
            ->flatMap(function ($order) {
                // Aplanar y retornar solo los productos de los items de orden
                return $order->orderItems->map(function ($orderItem) {
                    return $orderItem->product;
                });
            })
            ->unique('product_id'); // Elimina duplicados basados en el `product_id`
    
        // Retorna los productos en formato JSON
        return response()->json($purchasedProducts);
    }

    public function getAllProductsInCategory($categoryId)
{
    // Obtener todos los productos que pertenecen a la categoría especificada
    $productsInCategory = Product::where('category_id', $categoryId)->get();

    // Retornar los productos en formato JSON
    return response()->json($productsInCategory);
}


public function getCombinedProductsInCategory($categoryId)
{
    $userId = Auth::id(); // Obtiene el ID del usuario autenticado

    // Encuentra el usuario autenticado
    $user = User::findOrFail(4);

    // Obtener los productos comprados por el usuario en la categoría especificada
    $purchasedProducts = $user->orders()
        ->whereHas('orderItems.product', function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId); // Filtrar por la categoría
        })
        ->with(['orderItems.product' => function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId); // Filtrar también aquí para obtener solo productos de la categoría
        }])
        ->get()
        ->flatMap(function ($order) {
            // Aplanar y retornar solo los productos de los items de orden
            return $order->orderItems->map(function ($orderItem) {
                return $orderItem->product;
            });
        })
        ->unique('product_id'); // Elimina duplicados basados en el `product_id`

    // Obtener todos los productos de la categoría especificada
    $allProductsInCategory = Product::where('category_id', $categoryId)->get();

    // Combinar los productos de ambas colecciones respetando el orden: primero comprados, luego el resto
    $combinedProducts = $purchasedProducts->merge($allProductsInCategory)
                                          ->unique('product_id') // Elimina duplicados basados en `product_id`
                                          ->values(); // Reindexa la colección para asegurar un índice ordenado

    // Retornar los productos combinados en formato JSON
    return response()->json($combinedProducts);
}


public function getRecomendationByHistory($categoryId)
{
    // $user = Auth::user(); // Obtiene el usuario autenticado
    $user = User::getUserById(7); // El controlador delega la responsabilidad al modelo

    // Obtener los productos comprados en la categoría especificada
    $purchasedProducts = $user->getPurchasedProductsInCategory($categoryId);

    // Obtener todos los productos en la categoría especificada
    $allProductsInCategory = Product::getAllProductsInCategory($categoryId);

    // Combinar los productos de ambas colecciones
    $combinedProducts = $purchasedProducts->merge($allProductsInCategory)
                                          ->unique('product_id')
                                          ->values();

    return response()->json($combinedProducts);
}





    
  




    
}
