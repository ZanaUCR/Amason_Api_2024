<?php

use App\Http\Controllers\CartProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';


//Route::get('/cart/view', [CartProductsController::class, 'index'])->name('cartproducts.index');
//Route::post('/cart/add', [CartProductsController::class, 'addtocart'])->name('cart.add');
//Route::post('/cart/remove', [CartProductsController::class, 'removeFromCart'])->name('cart.remove');
