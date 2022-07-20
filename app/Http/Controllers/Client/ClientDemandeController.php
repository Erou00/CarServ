<?php

namespace App\Http\Controllers\Client;

use App\Events\PrivateDemande;
use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Role;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'admin_id' => $user->id
            ]
        );


            # code...
            foreach ($request->services_id as $service_id) {
                # code..
                $demande->services() -> attach($service_id);
            }


            $demandes = Demande::select('*')->where('etat','In progress')->get();

            //dd($vidanges);

            broadcast(new PrivateDemande($demande->load('user'),$demandes->count()))->toOthers();

            $details = [
                'id' => $demande->id,
                'name' => $demande->user->first_name.' '.$demande->user->last_name,
                'adress' => $demande->user->adress,
                'marque' => $demande->car->marque->name.' '.$demande->car->model->model,
                'date' => $demande->date,
                'services' => $demande->services,
                'tel' => $demande->user->phone_number,
                'comment' => $demande->comment
            ];
            //dd($details['id']);

            try {
                //code...
                Mail::to('carserv@service.com')->send(new \App\Mail\DemandeMail($details));
            } catch (\Exception $exception) {
                return $exception;

            }

            session()->flash('message','');

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
