<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//Rutas para review
Route::middleware('auth:sanctum')->post('/publishReview/{product_id}', [ReviewController::class, 'publishReview']);
Route::middleware('auth:sanctum')->put('/updateReview/{review_id}', [ReviewController::class, 'updateReview']);
Route::middleware('auth:sanctum')->delete('/deleteReview/{review_id}', [ReviewController::class, 'deleteReview']);
