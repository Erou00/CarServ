<?php

use App\Http\Controllers\Dashboard\AdminDemandeController;
use App\Http\Controllers\Dashboard\AdminOrderController;
use App\Http\Controllers\Dashboard\CarController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\MechanicController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Models\Car;
use App\Models\Demande;
use App\Models\Product;
use App\Models\Role;
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

        $rolesM = Role::where('name','=','mecanicien')->first();
        $rolesC = Role::where('name','=','client')->first();
        $mecaniciens = $rolesM->users()->get();
        $client = $rolesC->users()->get();
        return view('dashboard.index', [
            'users'=> $client,
            'vehicules'=> Car::all(),
            'mecaniciens'=>$mecaniciens,
            'vidanges'=> Demande::all(),
            'demandeEnattents'=> Demande::where('etat','In progress')->get(),
            'validees'=> Demande::where('etat','!=','In progress')

                                    ->where('etat','!=','Handling')
                                    ->where('etat','!=','Completed')
                                    ->where('etat','!=','Refused')->get(),
            'refusees'=> Demande::where('etat','Refused')->get(),
            'products'=>Product::all(),

        ]);;
    });



    Route::controller(AdminDemandeController::class)->prefix('demandes')->group(function () {


        Route::get('/search_unit', 'search_unit_by_key');
        Route::get('/demande-details/{id}','detailsDemande')->name('details.demande');
        Route::get('/new-demandes', 'index')->name('new.demandes.index');
        Route::get('/new-demandes-data','DenAttente');
        Route::delete('/demande-delete/{id}','deleteVidangeByAdmin');
        Route::get('/demande-details/{id}','DemandeDetails');
        Route::post('/confirm-demande/{id}','ConfirmDemande')->name('confirm.demande');

        Route::get('/all-demandes','AllDemandes')->name('all.demandes');

        Route::get('/update-demande/{id}','updateDemandeByAdminGet')->name('updateDemandeByAdminGet');
        Route::post('/update-demande/{id}','updateDemandeByAdminPost')->name('updateDemandeByAdminPost');

        Route::get('invoice/{id}','demandeInvoice')->name('invoiceDemande');


    });

    Route::controller(AdminOrderController::class)->prefix('orders')->name('order.')->group(function () {

        Route::get('/','index')->name('index');
        Route::get('/{id}/details','details')->name('details');
        Route::delete('/{id}','delete')->name('delete');

    });

    Route::get('/users',[ClientController::class,'AllClients']);

    Route::resource('clients',ClientController::class);
    Route::resource('services',ServiceController::class);
    Route::controller(CarController::class)->group(function () {

        Route::get('cars/for_sale','carForSale')->name('carForSale');

        Route::post('cars/for_sale/validate/{id}','carValidate')->name('validateCar');
        Route::resource('cars',CarController::class);

    });


    Route::resource('products',ProductController::class);
    Route::get('product/out-stock', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::get('product/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');




    Route::resource('mechanics',MechanicController::class);
    Route::resource('demandes',ServiceController::class);

});

Auth::routes();



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
