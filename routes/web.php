<?php

use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DistrictController;
use App\Http\Controllers\Backend\PermissionController;
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

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

    Route::middleware('auth')->group(function () {
        Route::get('/index', function () {
            return view('Backend.layouts.master');
        })->name('dashboard.index');
        Route::group(['middleware' => ['permission:admin_permission|create_country|edit_country|delete_country|active_country|disactive_country']], function () {
            Route::prefix('countries')->name('countries.')->group(function () {
                Route::get('/', [CountryController::class, 'index'])->name('index');
                Route::post('/', [CountryController::class, 'store'])->name('store');
                Route::put('/update', [CountryController::class, 'update'])->name('update');
                Route::delete('/delete', [CountryController::class, 'destroy'])->name('destroy');
                Route::delete('/delete-all', [CountryController::class, 'deleteAll'])->name('deleteAll');
                Route::put('/activeAll', [CountryController::class, 'activeAll'])->name('ativeAll');
                Route::put('/disactiveAll', [CountryController::class, 'disactiveAll'])->name('disativeAll');
                Route::get('/index-table', [CountryController::class, 'indexTable'])->name('indexTable');
            });
        });

        Route::group(['middleware' => ['permission:admin_permission|create_city|edit_city|delete_city|active_city|disactive_city']], function () {
            Route::prefix('cities')->name('cities.')->group(function () {
                Route::get('/', [CityController::class, 'index'])->name('index');
                Route::post('/', [CityController::class, 'store'])->name('store');
                Route::put('/update', [CityController::class, 'update'])->name('update');
                Route::delete('/delete', [CityController::class, 'destroy'])->name('destroy');
                Route::delete('/delete-all', [CityController::class, 'deleteAll'])->name('deleteAll');
                Route::put('/activeAll', [CityController::class, 'activeAll'])->name('ativeAll');
                Route::put('/disactiveAll', [CityController::class, 'disactiveAll'])->name('disativeAll');
                Route::get('/index-table', [CityController::class, 'indexTable'])->name('indexTable');
            });
        });

        Route::group(['middleware' => ['permission:admin_permission|create_district|edit_district|delete_district|active_district|disactive_district']], function () {
            Route::prefix('districts')->name('districts.')->group(function () {
                Route::get('/', [DistrictController::class, 'index'])->name('index');
                Route::post('/', [DistrictController::class, 'store'])->name('store');
                Route::put('/update', [DistrictController::class, 'update'])->name('update');
                Route::delete('/delete', [DistrictController::class, 'destroy'])->name('destroy');
                Route::delete('/delete-all', [DistrictController::class, 'deleteAll'])->name('deleteAll');
                Route::put('/activeAll', [DistrictController::class, 'activeAll'])->name('ativeAll');
                Route::put('/disactiveAll', [DistrictController::class, 'disactiveAll'])->name('disativeAll');
                Route::get('/index-table', [DistrictController::class, 'indexTable'])->name('indexTable');
                Route::get('/cities/get-cities', [DistrictController::class, 'get_cities'])->name('get_cities');
            });
        });

        Route::group(['middleware' => ['permission:admin_permission|create_customer|edit_customer|delete_customer|active_customer|disactive_customer']], function () {
            Route::prefix('customers')->name('customers.')->group(function () {
                Route::get('/', [CustomerController::class, 'index'])->name('index');
                Route::post('/', [CustomerController::class, 'store'])->name('store');
                Route::put('/update', [CustomerController::class, 'update'])->name('update');
                Route::delete('/delete', [CustomerController::class, 'destroy'])->name('destroy');
                Route::delete('/delete-all', [CustomerController::class, 'deleteAll'])->name('deleteAll');
                Route::put('/activeAll', [CustomerController::class, 'activeAll'])->name('ativeAll');
                Route::put('/disactiveAll', [CustomerController::class, 'disactiveAll'])->name('disativeAll');
                Route::get('/index-table', [CustomerController::class, 'indexTable'])->name('indexTable');
                Route::get('/cities/get-cities', [CustomerController::class, 'get_cities'])->name('get_cities');
                Route::get('/districts/get-districts', [CustomerController::class, 'get_districts'])->name('get_districts');
            });
        });

        Route::group(['middleware' => ['permission:admin_permission']], function () {
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::put('/update', [UserController::class, 'update'])->name('update');
                Route::delete('/delete', [UserController::class, 'destroy'])->name('destroy');
                Route::delete('/delete-all', [UserController::class, 'deleteAll'])->name('deleteAll');
                Route::put('/activeAll', [UserController::class, 'activeAll'])->name('ativeAll');
                Route::put('/disactiveAll', [UserController::class, 'disactiveAll'])->name('disativeAll');
                Route::get('/index-table', [UserController::class, 'indexTable'])->name('indexTable');
            });
        });

        Route::group(['middleware' => ['permission:admin_permission']], function () {
            Route::prefix('roles')->name('roles.')->group(function () {
                Route::get('/', [RoleController::class, 'index'])->name('index');
                Route::post('/', [RoleController::class, 'store'])->name('store');
                Route::put('/update', [RoleController::class, 'update'])->name('update');
                Route::delete('/delete', [RoleController::class, 'destroy'])->name('destroy');
                Route::delete('/delete-all', [RoleController::class, 'deleteAll'])->name('deleteAll');
                Route::get('/index-table', [RoleController::class, 'indexTable'])->name('indexTable');
            });
        });

        Route::group(['middleware' => ['permission:admin_permission']], function () {
            Route::prefix('permissions')->name('permissions.')->group(function () {
                Route::get('/', [PermissionController::class, 'index'])->name('index');
                Route::post('/', [PermissionController::class, 'store'])->name('store');
                Route::put('/update', [PermissionController::class, 'update'])->name('update');
                Route::delete('/delete', [PermissionController::class, 'destroy'])->name('destroy');
                Route::get('/index-table', [PermissionController::class, 'indexTable'])->name('indexTable');
            });
        });
    });
});




Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
