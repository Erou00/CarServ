<?php

use App\Http\Controllers\Client\CarController;
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
        Route::post('/orders', 'store');
    });


});
Auth::routes();
