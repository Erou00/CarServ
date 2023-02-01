<?php

use App\Http\Controllers\ProduitController;
use App\Models\Bs;
use App\Models\SousMagasin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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




// Route::get('/test',[ProduitController::class,'data2']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::get('/test', function(){
//     $sMagasins = SousMagasin::join('sous_magasin_user','sous_magasins.id','=','sous_magasin_user.sous_magasin_id')
//     ->where('sous_magasin_user.user_id',Auth::id())
//     ->get();

// dd($sMagasins);

// return response()->json([
// "error" => false,
// "SousMagasins" => $sMagasins ,

// ],200);


// });


