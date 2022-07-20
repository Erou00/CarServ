<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('services', function () {
    return view('services');
});

Route::post('/models',[HomeController::class, 'models'])->name('getModel');

Route::get('/car-for-sale',[HomeController::class,'carForSale'])->name('carForSale');
Route::get('/car-for-sale/{slug}',[HomeController::class,'carDetails'])->name('carDetails');
Route::get('/products',[HomeController::class,'products'])->name('products');
Route::get('/products/{slug}',[HomeController::class,'productDetails'])->name('productDetails');

Route::post('/cart',[CartController::class,'store'])->name('cart');
Route::get('/cart',[CartController::class,'index'])->name('cart');
Route::get('/checkout/get/items',[CartController::class,'getItemsFromCart']);
Route::post('/place-order',[CartController::class,'placeOrder']);


Route::get('services-details/{id}',[HomeController::class,'serviceDetails'])->name('serviceDetails');



Route::resource('users',UserController::class);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
