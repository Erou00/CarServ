<?php

use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->get('/user/revoke/',function(Request $request){

    $user = $request->user();
    $user->tokens()->delete();

    return 'tokens are deleted';
});


Route::middleware('auth:sanctum')->get('/demandes/{id}',function($id){

    $mecanicien = User::find($id);
                    $demandes = Demande::where('mecanicien_id','=',$mecanicien->id)
                    ->where(function($query) {
                            $query->where('etat','Affected')
                                    ->orWhere('etat','Handling');

                        })->with('car',function($query)
                        {
                            $query->with('marque')
                                ->with('model');
                        })
                        ->with('services')
                        ->orderBy('created_at', 'DESC')
                        ->get();

                return response()->json([
                    "error" => false,
                    "vidanges" =>  $demandes,
                ]);
});






