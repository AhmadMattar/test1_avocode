<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
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
        Route::post('', [CountryController::class, 'store'])->name('store');
        Route::put('/update', [CountryController::class, 'update'])->name('update');
        Route::delete('/delete', [CountryController::class, 'destroy'])->name('destroy');
        Route::get('/data', [CountryController::class, 'getCountries'])->name('data');
    });
});


Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    Route::prefix('cities')->name('cities.')->group(function(){
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::post('', [CityController::class, 'store'])->name('store');
        Route::put('/update', [CityController::class, 'update'])->name('update');
        Route::delete('/delete', [CityController::class, 'destroy'])->name('destroy');
        Route::get('/data', [CityController::class, 'getCities'])->name('data');
    });
});

// Route::resource('cities', CityController::class);

Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users-data', [UserController::class, 'getUsers'])->name('users.data');

Route::get('/', function () {
    return view('layouts.master');
});
