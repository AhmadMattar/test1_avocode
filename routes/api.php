<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;

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
});
