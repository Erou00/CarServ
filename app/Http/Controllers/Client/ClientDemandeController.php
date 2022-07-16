<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Role;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDemandeController extends Controller
{
    //

    public function clientDemandes()
    {
        # code...

        $demandes = Demande::where('user_id',Auth::user()->id)
                            ->with('user')->with('car',function($query)
                            {
                                $query->with('marque')
                                    ->with('model');
                            })
                            ->with('services')
                            ->orderBy('date', 'DESC')
                            ->get();

        return view('client.demandes.index')->with('demandes',$demandes);
    }


    public function createDemande()
    {
        $services = Service::all();
        return view('client.demandes.create',[
            'services' => $services
        ]);
    }

    public function storeDemandes(Request $request)
    {

        $this->validate($request,[

            'car_id'=>'required',
            'services_id'=>'required',
            'address'=>'required',
            'date'=>['after:' . date('Y-m-d HH:MM'),'required']
        ]);

        $roles = Role::where('name','=','admin')->first();
        $user = $roles->users()->first();
        $date = Carbon::parse($request->date);

        $demande = Demande::create([
            'car_id' => $request->car_id,
            'date' =>$date->format('Y-m-d H:i:s'),
            'address' => $request->address,
            'comment' => $request->commentaire,
            'user_id' => Auth::id(),
            'admin_id' => $user->id
            ]
        );


            # code...
            foreach ($request->services_id as $service_id) {
                # code..
                $demande->services() -> attach($service_id);
            }

            return redirect()->route('demandes.clientDemandes');

    }

    public function InProgress()
    {
        # code...
        $demandes = Demande::where('user_id',Auth::user()->id)
        ->Where('etat','In progress')
        ->orderBy('date', 'DESC')->get();


        //dd($vidanges);

        return view('client.demandes.InProgress')->with('demandes',$demandes);

    }
}
