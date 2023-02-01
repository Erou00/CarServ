<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function updateProfile(Request $request)
    {
        # code...
        //dd($request->all());
           # Validation
        if ($request->new_password != null  && $request->old_password != null) {
            # code...
           $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
            ]);

             #Match The Old Password
            if(!Hash::check($request->old_password, auth()->user()->password)){
                return back()->with("error", "L'ancien mot de passe ne correspond pas");
            }

            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->new_password)
            ]);

        }else{
            User::whereId(auth()->user()->id)->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
            ]);
        }







        return back()->with("status", "Modifier avec succès!");
    }
}
