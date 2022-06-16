<?php

use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\ServiceController;
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

    'middleware' => ['auth','admin'],
    'prefix' => 'dashboard',
    'name' => 'dashboard.'


], function ($router) {
    Route::get('/', function () {
        return view('dashboard.index');
    });

    Route::resource('clients',ClientController::class);
    Route::resource('services',ServiceController::class);
    Route::resource('demandes',ServiceController::class);

});

Auth::routes();



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
