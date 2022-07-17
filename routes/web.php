<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\CountryController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    Route::prefix('countries')->name('countries.')->group(function(){
        Route::get('/', [CountryController::class, 'index'])->name('index');
        Route::post('/', [CountryController::class, 'store'])->name('store');
        Route::put('/update', [CountryController::class, 'update'])->name('update');
        Route::delete('/delete', [CountryController::class, 'destroy'])->name('destroy');
        Route::delete('/delete-all', [CountryController::class, 'deleteAll'])->name('deleteAll');
        Route::put('/activeAll', [CountryController::class, 'activeAll'])->name('ativeAll');
        Route::put('/disactiveAll', [CountryController::class, 'disactiveAll'])->name('disativeAll');
        Route::get('/index-table', [CountryController::class, 'indexTable'])->name('indexTable');
    });

    Route::prefix('cities')->name('cities.')->group(function(){
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::post('/', [CityController::class, 'store'])->name('store');
        Route::put('/update', [CityController::class, 'update'])->name('update');
        Route::delete('/delete', [CityController::class, 'destroy'])->name('destroy');
        Route::delete('/delete-all', [CityController::class, 'deleteAll'])->name('deleteAll');
        Route::put('/activeAll', [CityController::class, 'activeAll'])->name('ativeAll');
        Route::put('/disactiveAll', [CityController::class, 'disactiveAll'])->name('disativeAll');
        Route::get('/index-table', [CityController::class, 'indexTable'])->name('indexTable');
    });

    Route::prefix('users')->name('users.')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/update', [UserController::class, 'update'])->name('update');
        Route::delete('/delete', [UserController::class, 'destroy'])->name('destroy');
        Route::delete('/delete-all', [UserController::class, 'deleteAll'])->name('deleteAll');
        Route::put('/activeAll', [UserController::class, 'activeAll'])->name('ativeAll');
        Route::put('/disactiveAll', [UserController::class, 'disactiveAll'])->name('disativeAll');
        Route::get('/index-table', [UserController::class, 'indexTable'])->name('indexTable');
        Route::get('/cities/get-cities', [UserController::class, 'get_cities'])->name('get_cities');
    });
});


Route::get('/', function () {
    return view('Backend.layouts.master');
});
