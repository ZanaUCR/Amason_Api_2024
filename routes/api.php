<?php

//use Illuminate\Http\Response;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CartProductsController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\UserController;



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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/cart', [CartProductsController::class, 'showCart'])->name('cart.showCart'); // Se elimina el userId de la ruta
    Route::post('/cart/add', [CartProductsController::class, 'addToCart'])->name('cart.addToCart');
    Route::post('/cart/update-units', [CartProductsController::class, 'updateUnits'])->name('cart.update.units');
    Route::post('/cart/remove-product', [CartProductsController::class, 'removeProductFromCart'])->name('cart.remove.product');
    Route::post('/cart/removeall', [CartProductsController::class, 'removeAllProductsFromCart'])->name('cart.removeAll.product');
    Route::get('/deliveryInformation', [UserController::class, 'getDeliveryInformation'])->name('deliveryInformation.get');
    Route::put('/deliveryInformation', [UserController::class, 'updateDeliveryInformation'])->name('deliveryInformation.update');
    Route::post('/order/process', [OrderController::class, 'processOrder'])->name('order.process');
    Route::get('/searchOrder/{order_id}', [OrderController::class, 'searchOrder'])->name('order.search');
    Route::post('/order/finish', [OrderController::class, 'finishOrder'])->name('order.finish');
    Route::post('/order/create', [OrderController::class, 'createOrder']);
    Route::get('/cart/products', [OrderController::class, 'searchProductInCartByuser_id']);
    Route::get('/order/pending', [OrderController::class, 'searchPendingOrderByUser']);
    

});

// Ruta para pruebas
// Route::post('/order/create', [OrderController::class, 'createOrder']);
Route::post('/order/cancel', [OrderController::class, 'cancelOrder']);
Route::post('/order/finish', [OrderController::class, 'finishOrder']);
Route::get('/product/{product_id}', [OrderController::class, 'searchProduct']);

Route::post('/payment/validate-card', [PaymentMethodController::class, 'validateCardNumber']);
Route::get('/order/{order_id}', [OrderController::class, 'searchOrder']);



Route::get('/categories', [CartProductsController::class, 'getCategories'])->name('categories.list');









//Route::resource('tickets', TicketController::class);
Route::middleware('auth:sanctum')->post('/tickets/store', [TicketController::class, 'store']);

Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);






// middleware(['auth:sanctum'])->
Route::get('/recommended/products/category/{categoryId}', [RecommendationController::class, 'getRecommendationByHistory']);
Route::get('/recommended/test/products/category/{categoryId}', [RecommendationController::class, 'testProductImages']);
Route::get('/recommended/tending/products', [RecommendationController::class, 'getTendingProducts']);


//Route::get('stores/{storeId}/top-selling-products', [ReportController::class, 'getTopSellingProductsByStore']);


Route::middleware('auth:sanctum')->group(function () {

    //Route::post('/tickets/{id}/assign', [TicketController::class, 'assignTicket']);
    Route::post('/tickets/{ticket_id}/messages', [TicketController::class, 'addMessage']);

    Route::get('/tickets/{ticket_id}/messages', [TicketController::class, 'getMessages']);
    Route::Post('/assign-ticket/{id}', [TicketController::class, 'assignTicket']);

    Route::get('/unassigned-tickets', [TicketController::class, 'unassignedTickets']);
    Route::get('/assigned-tickets', [TicketController::class, 'assignedTickets']);
    Route::put('/tickets/{id}/close', [TicketController::class, 'closeTicket']);

    Route::middleware(['auth:sanctum'])->get('/products/category/{categoryId}', [RecommendationController::class, 'getCombinedProductsInCategory']);

    Route::middleware('auth:sanctum')->get('/recommendationByCart', [RecommendationController::class, 'getRecommendationByCart']);

});




Route::middleware('auth:sanctum')->get('/user-tickets', [TicketController::class, 'userTickets']);

Route::get('/recommendations', [RecommendationController::class, 'getRecommendations']);

// Rutas para los productos


Route::get('/products/search', [ProductController::class, 'searchProducts']);

Route::post('/upload-image', [ProductController::class, 'uploadImage']);


// Obtener productos por tienda
Route::get('/products/store/{storeId}', [ProductController::class, 'getProductsByStore']);

// Editar un producto
Route::put('/products/{id}', [ProductController::class, 'editProduct']);
Route::post('/products/{id}/images', [ProductController::class, 'updateProductImages']); // Para imágenes
// Eliminar un producto
Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);

// Obtener productos por categoria 
Route::get('/products/category/{categoryId}', [ProductController::class, 'getProductsByCategory']);

// Agregar un nuevo producto (acceso sin autenticación si lo deseas)
Route::post('/products', [ProductController::class, 'store']);

//rutas para reviews
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews/publishReview/{product_id}', [ReviewController::class, 'publishReview']);
    Route::put('/reviews/updateReview/{review_id}', [ReviewController::class, 'updateReview']);
    Route::delete('/reviews/deleteReview/{review_id}', [ReviewController::class, 'deleteReview']);
    Route::get('/reviews/showReviews/{product_id}', [ReviewController::class, 'showReviews']);
    Route::get('/reviews/by-calification/{productId}', [ReviewController::class, 'showReviewsByCalification']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json(['user' => Auth::id()]);
});

// apis de gestion de tiendas
Route::post('/store', [StoreController::class, 'createStore']);
Route::get('/store/{sellerId}', [StoreController::class, 'storesBySellerId']);
Route::get('/store/id/{Id}', [StoreController::class, 'storesById']);
Route::delete('/store/{id}', [StoreController::class, 'deleteStore']);
Route::patch('/store/{id}', [StoreController::class, 'updateStore']);

//apis de gestion de variantes
Route::post('/products/{productId}/variation', [ProductController::class, 'createVariation']);
Route::put('/products/{productId}/variation', [ProductController::class, 'updateVariation']);
Route::delete('/products/{productId}/variation', [ProductController::class, 'deleteVariation']);