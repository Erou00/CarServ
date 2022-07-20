<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use \PDF;



class AdminDemandeController extends Controller
{

    public function index()
    {
        # code...

        return view('dashboard.demandes.index');


    }

    public function DenAttente()
    {
        # code...
        $demandes = Demande::select('*')->where('etat','In progress')->orderBy('date', 'desc')
        ->with('user')->get();
        //dd($vidanges);
        return $demandes;

    }

    public function search_unit_by_key(Request $request)
    {
    	$key = $request->q;
        $unit = Demande::join('users','users.id', '=', 'demandes.user_id')
                        ->where('users.first_name','LIKE',"%{$key}%")
                                    ->orWhere('users.last_name','LIKE',"%{$key}%")
                                    ->orWhere('users.adress','LIKE',"%{$key}%")
                                    ->get();

    	return response()->json([ 'demandes' => $unit ]);
    }

    public function AllDemandes()
    {
        # code...
        $demandes = Demande::with('user')->with('mechanic')->with('car',function($query)
        {
            $query->with('marque')->with('model');
        })->orderByDesc('date')->get();

        //dd($demandes);
        return view('dashboard.demandes.AllDemandes')->with('demandes',$demandes);
    }

    public function DemandeDetails($id)
    {
        # code...
        $demande = Demande::find($id);


        //dd($demande->car->marque);


        $mechanics = User::select('users.*')->join('role_user','users.id', '=', 'role_user.user_id')
                    ->join('roles','role_user.role_id', '=', 'roles.id')
                    ->where('roles.name', '=' ,'mecanicien')
                    ->get();

                   // dd($vidange->type);

        return view('dashboard.demandes.details',[

            'demande' => $demande,
            'mechanics' => $mechanics
        ]);

    }


    public function ConfirmDemande(Request $request,$id)
    {
        # code...
        $demande = Demande::find($id);
        $demande->motif = $request->commentaire;
        if ($request->etat == 'Validate') {

            $demande->mechanic_id = $request->mechanic_id;
            $demande->etat = "Affected";

        } else{
            $demande->mechanic_id = null;
            $demande->etat = $request->etat;
        }


        //broadcast(new PrivateVidangeEnAttente($vidanges))->toOthers();

        $details = [
            'commentaire' => $request->comment
        ];

        $subject = 'Your demande '.$request->etat;
        //dd($details['id']);
        Mail::to($demande->user->email)
        ->send(new \App\Mail\ConfirmMail($details,$subject));

        $demande->update();

        return redirect()->route('dashboard.new.demandes.index');

    }


    public function updateDemandeByAdminGet($id)
    {
        # code...
        $demande = Demande::find($id);
        $services = Service::all();
        return view('dashboard.demandes.updateDemande')->with(['demande'=>$demande, 'services'=>$services]);

    }

    public function updateDemandeByAdminPost($id,Request $request)
    {
        # code...
        $demande = Demande::find($id);

        //dd($demande);
        $this->validate($request,[

            'services_id'=>'required',
            'date'=>'required|after:today'

        ]);

        if ($request->services_id) {
            # code...
            $demande->services() -> detach();
            foreach ($request->services_id as $service_id) {
                        # code..
                        $demande->services() -> attach($service_id);
                 }
        }


        $demande->car_id = $request->car_id;
        $demande->date = $request->date;
        $demande->address = $request->address;
        $demande->comment= $request->comment;


        $demande->update();

        return redirect()->route('dashboard.new.demandes.index');

    }
    public function deleteDemandeByAdmin($id)
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


    public function demandeInvoice($id)
    {
        # code...
        $demande = Demande::find($id);


        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y'),
            'name'     => ucfirst($demande->user->first_name).' '.ucfirst($demande->user->last_name) ,
            'email'     => $demande->user->email,
            'phone'     => $demande->user->phone_number,
            'cin'     => $demande->user->cin,
            'adress'     => $demande->user->adress,
            'car'     =>$demande->car->marque->name .' '.$demande->car->model->mode,

            'services'     => $demande->services,
            'date'  => date('d/m/Y', strtotime($demande->date))
        ];

            //dd($data);

        //     $pdf = PDF::loadView('fiche', array('data' => $data));
        //     return $pdf->download('invoice.pdf');

        //$pdf = App::make('dompdf.wrapper');
        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/invoice', array('data' => $data));
        return $pdf->stream();

    }

}
