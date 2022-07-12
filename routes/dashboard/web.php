<?php

use App\Http\Controllers\Dashboard\AdminDemandeController;
use App\Http\Controllers\Dashboard\CarController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\MechanicController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ServiceController;
use Illuminate\Support\Facades\Auth;
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



Route::prefix('dashboard')->middleware(['auth','admin'])->name('dashboard.')->group(function ($router) {
    Route::get('/', function () {
        return view('dashboard.index');
    });



    Route::controller(AdminDemandeController::class)->prefix('demandes')->group(function () {
        Route::get('/orders/{id}', 'show');
        Route::post('/orders', 'store');
    });

    Route::resource('clients',ClientController::class);
    Route::resource('services',ServiceController::class);
    Route::controller(CarController::class)->group(function () {

        Route::get('for_sale','carForSale')->name('carForSale');
        Route::resource('cars',CarController::class);

    });


    Route::resource('products',ProductController::class);
    Route::resource('mechanics',MechanicController::class);
    Route::resource('demandes',ServiceController::class);

});

Auth::routes();



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
