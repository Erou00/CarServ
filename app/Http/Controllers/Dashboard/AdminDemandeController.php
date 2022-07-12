<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDemandeController extends Controller
{
    public function AllDemandes()
    {
        # code...
        $demandes = Demande::with('user')->with('mechanic')->with('car',function($query)
        {
            $query->with('mark')->with('cmodel');
        })->orderByDesc('date')->get();
        return view('vidange.tousVidanges')->with('demandes',$demandes);
    }

    public function DemandeDetails($id)
    {
        # code...
        $demande = Demande::find($id);

        //dd($vidange->products);

        if ( $demande->etat == "Nouvelle") {
            # code...
            $demande->etat = "En cours";
            $demande->update();
        }


        $mechanics = User::select('users.*')->join('role_user','users.id', '=', 'role_user.user_id')
                    ->join('roles','role_user.role_id', '=', 'roles.id')
                    ->where('roles.name', '=' ,'mecanicien')
                    ->get();

                   // dd($vidange->type);

        return view('',[

            'demande' => $demande,
            'mechanics' => $mechanics
        ]);

    }


    public function Confirmdemande(Request $request,$id)
    {
        # code...
        $demande = Demande::find($id);
        $demande->motif = $request->commentaire;
        if ($request->mechainc_id) {

            $demande->mechanic_id = $request->mechanic_id;
            $demande->etat = "Affected";

        } else{
            $demande->etat = $request->etat;
        }


        //broadcast(new PrivateVidangeEnAttente($vidanges))->toOthers();

        // $details = [
        //     'commentaire' => $request->commentaire
        // ];

        // $subject = 'Votre demande est '.$request->etat;
        // //dd($details['id']);
        // Mail::to('alixander.rofix@gmail.com')
        // ->send(new \App\Mail\ConfirmMail($details,$subject));

        $demande->update();

        return redirect(route('demandes.en.attente'));

    }

    public function deleteVidangeByAdmin($id)
    {
        # code...
        $demande = Demande::find($id);
        //$message = Message::where('demande_id',$demande ->id);
        //$message->delete();
        $demande->delete();

        return response()->json([
            "error" => false,
            "demande" => "deleted",
        ]);
    }
}
