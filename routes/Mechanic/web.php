<?php


use App\Http\Controllers\Mechanic\MechanicDemandeController;
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

    'middleware' => ['auth'],

], function ($router) {



    Route::controller(MechanicDemandeController::class)->prefix('mechanic')->name('mechanic.')->group(function () {

        Route::get('/demandes-affected','getMechanicDemandes')->name('MechanicDemandeAffected');
        Route::get('/demande/{id}/details','getDemandeDetails')->name('DemandeDetails');
        Route::post('/demande/{id}/changeEtat','changeEtat')->name('changeEtat');

    });

});


Auth::routes();
