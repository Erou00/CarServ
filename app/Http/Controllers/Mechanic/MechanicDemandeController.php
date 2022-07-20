<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicDemandeController extends Controller
{
    //
    public function getMechanicDemandes()
    {
        # code...
        $demandes = Demande::with('mechanic')
        ->with('car',function($query)
        {
            # code...
            $query->with('marque')->with('model');
        })
        ->with('services')
        ->where('mechanic_id',Auth()->user()->id)->orderByDesc('date')->get();

        return view('mechanic.demandes')->with('demandes',$demandes);
    }

    public function getDemandeDetails($id)
    {
        # code...
        $demande = Demande::find($id);
        return view('mechanic.demande-details')->with('demande',$demande);
    }

    public function changeEtat(Request $request, $id)
    {
        # code...
        $demande = Demande::find($id);


        //dd($request->all());
        if ($request->etat == 'Handling') {
            # code...
            $demande->etat = 'Handling';
        }
        elseif ($request->etat == 'Completed') {
            # code...
            $demande->etat = 'Completed';
        }

        $demande->update();

        return redirect()->route('mechanic.MechanicDemandeAffected');
    }


}
