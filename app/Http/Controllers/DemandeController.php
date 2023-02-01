<?php

namespace App\Http\Controllers;

use App\Models\Bs;
use App\Models\BsDetail;
use App\Models\Demande;
use App\Models\DemandeDetail;
use App\Models\Entite;
use App\Models\Facture;
use App\Models\HistoriqueDemande;
use App\Models\Magasin;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DemandeController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_demandes')->only(['index']);
        $this->middleware('permission:create_demandes')->only(['create', 'store']);
        $this->middleware('permission:update_demandes')->only(['edit']);
        $this->middleware('permission:delete_demandes')->only(['delete', 'bulk_delete']);
        $this->middleware('permission:extern_demandes')->only(['DemandesExterneMesDemandes','DemandesExterneCreate','myDemande']);


    }// end of __construct
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            # code...


            $commandes = Demande::when($request->no_commande, function ($q) use ($request) {
                return $q->where('no_commande',intval(substr($request->no_commande, 0,strpos($request->no_commande,"/")+1)))
                        ->where('annee','like','%' .substr($request->no_commande, strpos($request->no_commande, "/") + 1)  . '%');

            })->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date_commande', $request->date);
            })->when($request->entite_id, function ($q) use ($request) {
                return $q->where('entite_id', $request->entite_id);
            })->when($request->annee, function ($q) use ($request) {
                return $q->where('annee', $request->annee);
            })->where('magasin_id',Auth::user()->magasin_id)
            ->where(function($query) use ($request) {
                $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
                    return $item->id;
                })->toArray();
                if (!$request->sous_magasin_id) {
                    $query->whereIn('sous_magasin_id',$userSousMagasin);
                }else{
                    $query->where('sous_magasin_id', $request->sous_magasin_id);
                }

            })->orderBy('annee','DESC')->orderBy('no_commande','DESC')->get();

           //dd($marches);
            return DataTables::of($commandes)
            ->addIndexColumn()
            ->editColumn('no_commande',function(Demande $c){
                return $c->no_commande.'/'.$c->annee  ;
            })->editColumn('date_commande',function(Demande $c){
                return Carbon::parse($c->date_commande)->format('d/m/Y');
            })->addColumn('entite', function (Demande $c) {
                return ($c->entite) ? $c->entite->nom : '';
            })->addColumn('magasin', function (Demande $c) {
                return ($c->sousMagasin()) ? $c->sousMagasin->nom : '';
            })
            ->addColumn('commande_details', function (Demande $c) {
                $details = $c->demandeDetails;
                $facture = $c->facture ? $c->facture : '';
                return view('dashboard.demandes.data_table.commandeDetails', compact('details','facture'));;
            })
            ->addColumn('actions', 'dashboard.demandes.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();
        }
        $entites = Entite::all();
        $annees = Demande::select('annee')->groupBy('annee')->get();
        $magasins = Magasin::all();

        return view('dashboard.demandes.index',compact('entites','annees','magasins'));
    }

    public function AllDemandes()
    {
        # code...

        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
            return $item->id;
        })->toArray();

        $demandes =  Demande::selectRaw("id,CONCAT(no_commande, '/',annee) AS no_commande")
                            ->whereYear('date_commande',Carbon::now()->format('Y'))
                            ->where('magasin_id', Auth::user()->magasin_id)
                            ->whereIn('sous_magasin_id',$userSousMagasin)
                            ->doesntHave('bs')
                            ->get();

        return response()->json([
            'error' => false,
            'demandes' => $demandes
        ],200);
    }

    public function demandeDetails($id)
    {
        # code...
        $demande = Demande::where('id',$id)
                            ->whereYear('date_commande',Carbon::now()->format('Y'))
                            ->with('entite')
                            ->get();

        $pID = [];
        foreach ($demande->first()->demandeDetails as $d) {
            array_push($pID,$d->produit_id);
        }

        $lastDemande = DB::table('demandes')
                            ->join('bs', 'demandes.id', '=', 'bs.demande_id')
                            ->join('bs_details', 'bs.id', '=', 'bs_details.bs_id')
                            ->join('produits', 'bs_details.produit_id', '=', 'produits.id')
                            ->where('demandes.annee',Carbon::now()->format('Y'))
                            ->where('demandes.entite_id',$demande->first()->entite_id)
                            ->where('bs_details.produit_id',$pID)
                            ->where('bs.sortie','not like','annulation')
                            ->select(DB::raw('DATE_FORMAT(bs.date, "%d/%m/%Y") as date'),
                             DB::raw("CONCAT(bs.no_bl, '/', bs.annee) AS no_bl"),
                            'produits.designation','bs_details.qte_donnee')
                            ->orderBy('bs.annee','DESC')->orderBy('bs.no_bl','DESC')-> get();

        //dd($lastDemande);


        $stocks = DB::table('produits')
            ->join('demande_details', 'produits.id', '=', 'demande_details.produit_id')
            ->join('unite_reglementaires', 'unite_reglementaires.id', '=', 'produits.unite_reglementaire_id')
            ->select('produits.id','produits.designation',
            'demande_details.*','unite_reglementaires.code',
            DB::raw("COALESCE((SELECT SUM(bs_details.qte_donnee) FROM bs_details,bs
                            WHERE bs_details.produit_id = produits.id
                            AND bs_details.bs_id = bs.id
                            AND bs.sortie = 'preparation'
                            GROUP BY bs_details.produit_id),0) as product_stock"),
                            DB::raw("COALESCE((SELECT SUM(bs_details.qte_donnee) FROM bs_details,bs
                            WHERE bs_details.produit_id = produits.id
                            AND bs_details.bs_id = bs.id
                            AND bs.demande_id = ".$id."
                            GROUP BY bs_details.produit_id),0) as qte_deja_donnee"),
                            DB::raw("COALESCE((select qte from stocks where stocks.produit_id = produits.id and magasin_id =".Auth::user()->magasin_id."  ),0) as qte")
                    )
            ->where('demande_details.demande_id',$id)
            ->get();


        return response()->json([
            "error" => false,
            "details" => $stocks ,
            "demande" => $demande,
            "lastDemande" => $lastDemande,

            ],200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function FetchDemandes()
     {
        # code...
        $demandes = Demande::orderBy('created_at','desc')->with('demandeDetails',function($q){
            $q->with('produit',function($q){
               $q->with('uniteReglementaire');
               $q->with('stock');
            });
       }
       )->get();

       return response()->json([
        "error" => false,
        "demandes" => $demandes

        ],200);
     }
    public function demandePeriodique()
    {
        # code...
        return view('dashboard.demandes.create_demande_periodique');
    }
    public function create()
    {


        return view('dashboard.demandes.create');
    }


    public function storeDemandePeriodique(Request $request)
    {
        # code...


        $validator = Validator::make($request->demande, [
            'date_commande' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->messages()
            ],400);
        }
        else {
            # code...


            foreach ($request->entites as $key => $e_id) {
                # code...
               $n = Demande::where('annee',Carbon::now()->format('Y'))
                        ->where('magasin_id',Auth::user()->magasin_id)
                        ->where('sous_magasin_id',$request->demande['sous_magasin_id'])->count();
               $dId = ($n == 0 ) ? 1 :  $n+1 ;

                $demandeData = $request->demande;
                $demandeData['no_commande'] = $dId;
                $demandeData['annee'] = Carbon::now()->format('Y');
                $demandeData['entite_id'] = $e_id;
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
            }




              return response()->json([
                'error' => false,
                'messages' => ''
            ],200);



        }
    }

    public function store(Request $request)
    {
        //

    }

    public function storeWithDetails( Request $request)
    {
        # code...
        // dd($request->all());
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
        $factures = Facture::all();


        return view('dashboard.demandes.show',compact('demande','entites','factures'));
    }

    public function myDemande($id)
    {
        //
        $demande = Demande::where('id',$id)->where('user_id',Auth::id())->first();
        $entites = Entite::all();

        //dd($demande);
        return view('dashboard.demandes.my_demande',compact('demande','entites'));
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
         Bs::where('demande_id',$demande->id)->update([
                                            'entite_id' => $demande->entite_id,
                                            'sous_magasin_id' => $demande->sous_magasin_id,
                                        ]);

        $bs =  Bs::where('demande_id',$demande->id)->get();

        foreach ($bs as $key => $b) {
            # code...
            BsDetail::where('bs_id',$b->id)->update([
                'sous_magasin_id' => $b->sous_magasin_id,
            ]);
        }


        HistoriqueDemande::create([
            'demande_id' => $demande->id,
            'user_id' =>  Auth::id()
        ]);
        return redirect()->back();
    }

    public function updateEntite($id,Request $request)
    {
        # code...

        $demande = Demande::findOrfail($id);
        $demande->update($request->all());

        return response()->json([
            'error' => false,
            'message' => 'Modifier avec succès'
        ]);

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

    public function deleteProduit($id)
    {
        # code...
        $details = DemandeDetail::findOrFail($id);
        if ($details->demande->bs->count() > 0) {
            # code...
            return response()->json([
                'error' => true,
                'message' => 'La demande deja traiter'
            ]);
        }
        $details->delete();

        return response()->json([
            'error' => false,
            'message' => 'Supprimer avec succés'
        ]);
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
            'traitePar'     => 'Traité par : <strong>'.ucwords($commande->user->nom).' '.ucwords($commande->user->prenom).'</strong>',
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

    public function demandeUpdateAfterPrint($id)
    {
        # code...
        $demande = Demande::findOrFail($id);
        if ($demande->imp == false) {
            # code...
            $demande->imp = true;
            $demande->update();
        }

    }


    public function importerDemande(Request $request)
    {
        # code...

        $demande = Demande::findOrfail($request->demande_id);


        $n = Demande::where('annee',Carbon::now()->format('Y'))
                                ->where('magasin_id',$demande->magasin_id)
                                ->where('sous_magasin_id',$demande->sous_magasin_id)->count();
        $dId = ($n == 0 ) ? 1 :  $n+1 ;

        $newDemande = Demande::create([
            "annee" =>  Carbon::now()->format('Y'),
            "no_commande" => $dId,
            "user_id" => Auth::id(),
            "date_commande" =>  Carbon::now(),
            "entite_id" =>  $demande->entite_id,
            "magasin_id" =>  $demande->magasin_id,
            "sous_magasin_id" =>  $demande->sous_magasin_id,
        ]);

        if ($demande->demandeDetails) {
            # code...
            foreach ($demande->demandeDetails as $details) {
               DemandeDetail::create([
                'produit_id'=> $details->produit_id,
                'demande_id'=> $newDemande->id,
                'qte_demandee'=> $details->qte_demandee,
               ]);
                # code...
            }
          }


          Session::flash('success','Ajouter avec succés');
          return redirect()->route('demandes.show',$newDemande->id);


    }




    public function getNumCommande($sousMagasinId)
    {
        # code...
        $n = Demande::where('annee',Carbon::now()->format('Y'))
            ->where('magasin_id',Auth::user()->magasin_id)
            ->where('sous_magasin_id',$sousMagasinId)->count();
        $no_commande = ($n == 0 ) ? 1 :  $n+1 ;

        return response()->json([
            'error' =>false,
            'no_commande' => $no_commande.'/'.Carbon::now()->format('Y')
        ]);
    }


    public function consulterAutreMagasin(Request $request)
    {

        if (Auth::user()->hasRole('master')) {
            # code...
            if ($request->ajax()) {
                $commandes = Demande::when($request->no_commande, function ($q) use ($request) {
                    return $q->where('no_commande',intval(substr($request->no_commande, 0,strpos($request->no_commande,"/")+1)))
                            ->where('annee','like','%' .substr($request->no_commande, strpos($request->no_commande, "/") + 1)  . '%');

                })->when($request->date, function ($q) use ($request) {
                    return $q->whereDate('date_commande', $request->date);
                })->when($request->entite_id, function ($q) use ($request) {
                    return $q->where('entite_id', $request->entite_id);
                })->when($request->annee, function ($q) use ($request) {
                    return $q->where('annee', $request->annee);
                })->when($request->magasin_id, function ($q) use ($request) {
                    return $q->where('magasin_id', $request->magasin_id);
                })->when($request->sous_magasin_id, function ($q) use ($request) {
                        return $q->where('sous_magasin_id', $request->sous_magasin_id);
                })->addColumn('magasin', function (Demande $c) {
                    return ($c->sousMagasin()) ? $c->sousMagasin->nom : '';
                })

                ->orderBy('annee','DESC')->orderBy('no_commande','DESC')->get();

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
                    $facture = $c->facture ? $c->facture : '';
                    return view('dashboard.demandes.data_table.commandeDetails', compact('details','facture'));;
                })
                ->addColumn('actions', 'dashboard.demandes.data_table.actions')
                ->rawColumns(['actions'])
                ->toJson();
            }else{

                $entites = Entite::all();
                $annees = Demande::select('annee')->groupBy('annee')->get();
                $magasins = Magasin::all();

                return view('dashboard.demandes.autre_magasin',compact('entites','annees','magasins'));

            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
