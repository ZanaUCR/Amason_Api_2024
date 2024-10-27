<?php

//use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

use App\Http\Controllers\CartProductsController;

use App\Http\Controllers\RecommendationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Ruta para obtener el usuario autenticado
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta de prueba para verificar si el servidor está en funcionamiento
Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/tickets/store', [TicketController::class, 'store']);
Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);


Route::post('/cart/add', [CartProductsController::class, 'addtocart'])->name('cart.add');

Route::get('/cart/{userId}', [CartProductsController::class, 'index'])->name('cart.index');
Route::post('/cart/remove', [CartProductsController::class, 'removeProductUnits'])->name('cart.remove');
Route::post('/cart/remove-product', [CartProductsController::class, 'removeProductFromCart'])->name('cart.remove.product');
Route::post('/cart/removeall', [CartProductsController::class, 'removeAllProductsFromCart'])->name('cart.removeAll.product');






Route::middleware(['auth:sanctum'])->get('/products/category/{categoryId}', [RecomendationController::class, 'getCombinedProductsInCategory']);


Route::get('/recommendations', [RecommendationController::class, 'getRecommendations']);


