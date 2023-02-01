<?php

namespace App\Http\Controllers;

use App\Models\Bs;
use App\Models\Demande;
use App\Models\DemandeDetail;
use App\Models\Entite;
use App\Models\HistoriqueDemande;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class DemandeExternController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_demandes_extern')->only(['index']);
        $this->middleware('permission:create_demandes_extern')->only(['create', 'store']);
        $this->middleware('permission:update_demandes_extern')->only(['edit','update']);
        $this->middleware('permission:delete_demandes_extern')->only(['destroy', 'deleteDetails']);


    }// end of __construct

    public function index(Request $request)
    {
        //
                # code...
                if ($request->ajax()) {
                    # code...
                           # code...
                           $commandes = Demande::when($request->no_commande, function ($q) use ($request) {
                            return $q->where('no_commande',intval(substr($request->no_commande, 0,strpos($request->no_commande,"/")+1)))
                                    ->where('annee','like','%' .substr($request->no_commande, strpos($request->no_commande, "/") + 1)  . '%');

                        })->when($request->date, function ($q) use ($request) {
                            return $q->whereDate('date_commande', $request->date);
                        })->when($request->entite_id, function ($q) use ($request) {
                            return $q->where('entite_id', $request->entite_id);
                        })->where('user_id',Auth::id())->orderBy('date_commande','DESC')->get();

                       //dd($marches);
                        return DataTables::of($commandes)
                        ->addIndexColumn()
                        ->editColumn('no_commande',function(Demande $c){
                            return $c->no_commande.'/'.$c->annee  ;
                        })->editColumn('date_commande',function(Demande $c){
                            return Carbon::parse($c->date_commande)->format('d/m/Y');
                        })->addColumn('entite', function (Demande $c) {
                            return ($c->entite) ? $c->entite->nom : '';
                        })
                        ->addColumn('commande_details', function (Demande $c) {
                            $details = $c->demandeDetails;
                            return view('dashboard.demandes.data_table.commandeDetails', compact('details'));;
                        })
                        ->addColumn('actions', 'dashboard.demandes_extern.data_table.actions')
                        ->rawColumns(['actions'])
                        ->toJson();

                }
                $annees = Demande::select('annee')->groupBy('annee')->get();

                return view('dashboard.demandes_extern.index',compact('annees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('dashboard.demandes_extern.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'no_commande' => 'required',
            'date_commande' => 'required',
            'entite_id'   => 'required',
            'demandeDetails.*.produit_id' => 'required',
            'demandeDetails.*.qte_demandee' => 'required'
        ]);


            # code...
            $n = Demande::where('annee',Carbon::now()->format('Y'))
                        ->where('magasin_id',Auth::user()->magasin_id)
                        ->where('sous_magasin_id',$request->sous_magasin_id)->count();
            $dId = ($n == 0 ) ? 1 :  $n+1 ;
            $demandeData = $request->except('demandeDetails');
            $demandeData['no_commande'] = $dId;
            $demandeData['annee'] = Carbon::now()->format('Y');
            $demandeData['user_id'] = Auth::id();
            $demandeData['magasin_id'] = Auth::user()->magasin_id;

            $demande = Demande::create($demandeData);

              if ($request->demandeDetails) {
                # code...
                foreach ($request->demandeDetails as $details) {
                   DemandeDetail::create([
                    'produit_id'=> $details['produit_id'],
                    'demande_id'=> $demande->id,
                    'qte_demandee'=> $details['qte_demandee'],
                   ]);
                    # code...
                }
              }

              if ($request->imprimer) {
                # code...
                return $this->rapport($demande->id,'fun');
              }

              session()->flash('success',__('Ajouter avec succés'));
              return redirect()->back();





    }

    public function addToDetails( Request $request, $id)
    {
        # code...
        $demande = Demande::findOrfail($id);
        if ($demande->bs->count() > 0) {
            # code...
            return response()->json([
                'error' => true,
                'message' => 'La demande deja traiter'
            ]);
        }
        foreach ($request->demandeDetails as $details) {
          $exist = DemandeDetail::where('demande_id',$id)->where('produit_id',$details['produit_id'])->first();
            if (!$exist) {
                # code...
                DemandeDetail::create([
                    'produit_id'=> $details['produit_id'],
                    'demande_id'=> $id,
                    'qte_demandee'=> $details['qte_demandee'],
                ]);
            }
         }

        return response()->json([
            'error' => false,
            'message' => 'Ajouter avec succés'
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $demande = Demande::findOrfail($id);
        $entites = Entite::all();


        return view('dashboard.demandes_extern.show',compact('demande','entites'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'date_commande' => 'required',
            'entite_id'   => 'required',
        ]);
        $demande = Demande::findOrfail($id);



        $demande->update($request->all());
        Bs::where('demande_id',$demande->id)->update(['entite_id' => $demande->entite_id]);

        HistoriqueDemande::create([
            'demande_id' => $demande->id,
            'user_id' =>  Auth::id()
        ]);
        return redirect()->back();
    }

    public function updateDetails($id,Request $request)
    {
        # code...
        // dd($request->all());
        $details = DemandeDetail::findOrFail($id);
        // if ($details->demande->bs->count() > 0) {
        //     # code...
        //     Session::flash('error','Demande déjà Traitée');
        //     return redirect()->back();
        // }
        $details->update($request->all());

        session()->flash('success',__('Modifier avec succès'));

        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $demande = Demande::findOrFail($id);
        if ($demande->bs->count() > 0) {
            Session::flash('error','Demande déjà Traitée');
            return redirect()->back();
        }

        Demande::where('annee',$demande->annee)
                ->where('no_commande','>',$demande->no_commande)->decrement('no_commande');

        DemandeDetail::where('demande_id',$id)->delete();
        $demande->delete();
        session()->flash('success',__('Supprimé avec succès'));


        return redirect()->route('demandes.index');
    }

    public function deleteDetails($id)
    {
        //
        $details = DemandeDetail::findOrFail($id);
        if ($details->demande->bs->count() > 0) {
            # code...
            Session::flash('error','Demande déjà Traitée');
            return redirect()->back();
        }

        $details->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return redirect()->back();
    }


    public function rapport($id,$from=null)
    {
        # code...
         $commande = Demande::findOrFail($id);

         $commandeDetails = Demande::where('id',$commande->id)->with('demandeDetails',
                                    function($q){
                                        $q->with('produit',function($q){
                                            $q->with('uniteReglementaire');
                                        });
                                    })->first();



        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y'),
            'duplicata' => ($commande->imp) ? 'duplicata' : '',
            'name'     => $commande->no_commande.'/'.$commande->annee,
            'date_commande'     => Carbon::parse($commande->date_commande)->format('d/m/Y'),
            'entite'     => $commande->entite->nom,
            'details'     => $commandeDetails,
            'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y')))
        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/demande', array('data' => $data));

        $directory =  'documents';
        $storage = File::allFiles($directory);

        if($storage){
            File::delete($storage);
        }

        if ($from) {
            # code...
            $pdf->save('documents/document.pdf');
            Session::flash('download.in.the.next.request','documents/document.pdf');
            return redirect()->back();
        }
        return $pdf->stream();
    }

}
