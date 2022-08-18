<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

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
Route::post('user/register', [AuthController::class, 'register']);
Route::post('user/login', [AuthController::class, 'login']);
Route::post('user/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('user/check-code', [AuthController::class, 'checkCode']);
Route::post('user/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('user/logout', [AuthController::class, 'logout']);
    Route::post('user/change-password', [AuthController::class, 'changePassword']);
    Route::get('user/profile', [CustomerController::class, 'profile']);
    Route::post('user/update-profile', [CustomerController::class, 'updateProfile']);

    Route::get('user/get-locations', [CustomerController::class, 'getLocations']);

    Route::get('products', [ProductController::class, 'index']);
    Route::post('products/add-to-cart', [ProductController::class, 'addToCart']);
    Route::get('products/get-cart-content', [ProductController::class, 'getCart']);
    Route::post('products/update-cart-content', [ProductController::class, 'updateCart']);
    Route::post('products/delete-from-cart-content', [ProductController::class, 'deleteProductFromCart']);
    Route::post('products/clear-cart', [ProductController::class, 'clearCart']);

    Route::post('cart/checkout', [ProductController::class, 'checkout']);

    Route::get('orders/get-orders', [OrderController::class, 'getOrders']);
    Route::post('orders/get-order-details', [OrderController::class, 'getOrderDetails']);
});

// Route::get('/test', function() {
//     return auth()->user()->first_name .' '.auth()->user()->last_name;
// })->middleware('auth:sanctum');
