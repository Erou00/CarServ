<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\CarController;
use App\Http\Controllers\Client\ChatController;
use App\Http\Controllers\Client\ClientDemandeController;
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



Route::group([

    'middleware' => ['auth','client'],

], function ($router) {


    Route::resource('cars',CarController::class);

    Route::controller(ClientDemandeController::class)->prefix('demandes')->name('demandes.')->group(function () {
        Route::get('/', 'clientDemandes')->name('clientDemandes');
        Route::get('/create', 'createDemande')->name('createtDemandes');
        Route::post('/store', 'storeDemandes')->name('storeDemandes');
        Route::get('/in-progress', 'InProgress')->name('InProgress');

    });

    Route::controller(ChatController::class)->prefix('chat')->name('chat.')->group(function () {

        Route::get('/{id?}', 'chatPage')->name('chatPage');
        Route::get('/private-user-messages/{userId}/{carId}', 'privateUserMessages')->name('messages');
        Route::post('/private-user-messages/{id}', 'sendPrivateMessage');

    });

    Route::get('check', [CartController::class,'checkout'])->name('checkout');

    Route::post('/process/user/payment/',[CartController::class,'processPayment'])->name('processPayment');

    Route::get('/orders',[CartController::class,'orders'])->name('clientOrders');


});
Auth::routes();
