<?php

use App\Http\Controllers\Client\CarController;
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

    'name' => 'dashboard.'


], function ($router) {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::resource('cars',CarController::class);


});
Route::post('/models',[CarController::class, 'models'])->name('getModel');
Auth::routes();
