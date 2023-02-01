<?php

namespace App\Http\Controllers;

use App\Models\Bl;
use App\Models\BlDetail;
use App\Models\Commande;
use App\Models\CommandeDetail;
use App\Models\Fournisseur;
use App\Models\HistoriqueCommande;
use App\Models\Magasin;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Tva;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use \PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class CommandeController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_commandes')->only(['index']);
        $this->middleware('permission:create_commandes')->only(['create', 'store','storeWithStock']);
        $this->middleware('permission:update_commandes')->only(['edit', 'update']);
        $this->middleware('permission:delete_commandes')->only(['delete', 'bulk_delete']);

    }// end of __construct
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fournisseurs = Fournisseur::all();
        $magasins = Magasin::all();
        $annees = Commande::select('annee')->groupBy('annee')->get();




        return view('dashboard.approvisionnement.commandes.index',compact('fournisseurs','magasins','annees'));
    }

    public function data(Request $request)
    {


        $commandes = Commande::when($request->no_commande, function ($q) use ($request) {
            return $q->where('no_commande',$request->no_commande);
        })->when($request->objet, function ($q) use ($request) {
            return $q->where('objet','like','%' . $request->objet . '%');
        })->when($request->date, function ($q) use ($request) {
            return $q->whereDate('date_commande', $request->date);
        })->when($request->fournisseur_id, function ($q) use ($request) {
            return $q->where('fournisseur_id', $request->fournisseur_id);
        })->when($request->annee, function ($q) use ($request) {
            return $q->where('annee', $request->annee);
        })
        ->where('magasin_id',Auth::user()->magasin_id)
        ->where(function($query) use ($request) {
            $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
                return $item->id;
            })->toArray();
            if (!$request->sous_magasin_id) {
                $query->whereIn('sous_magasin_id',$userSousMagasin);
            }else{
                $query->where('sous_magasin_id', $request->sous_magasin_id);
            }

        })->orderBy('date_commande','DESC')->orderBy('no_commande','DESC')->get();

       //dd($marches);
        return DataTables::of($commandes)
        ->addIndexColumn()
        ->editColumn('no_commande', function (Commande $c) {

            return $c->no_commande.'/'.$c->annee;
        })
        ->editColumn('date_commande', function (Commande $c) {

            return  Carbon::parse($c->date_commande)->format('d/m/Y');
        })->addColumn('magasin', function (Commande $c) {

            return $c->sousMagasin() ? $c->sousMagasin->nom:'';
        })
        ->addColumn('objet', function (Commande $c) {
            return ( Str::length($c->objet) >= 30) ? substr($c->objet,0,30).'...' : $c->objet ;
        })
        ->addColumn('fournisseur', function (Commande $c) {
            return $c->fournisseur->nom;
        })
        ->addColumn('commande_details', function (Commande $c) {
            $details = $c->commandeDetails;
            return view('dashboard.approvisionnement.commandes.data_table.commandeDetails', compact('details'));;
        })
        ->addColumn('actions', 'dashboard.approvisionnement.commandes.data_table.actions')
        ->rawColumns(['actions'])
        ->toJson();

    }// end of data


    public function allCommandes()
    {
        # code...
        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
            return $item->id;
        })->toArray();

        $commandes = DB::table('commandes')
        ->selectRaw("(SELECT SUM(commande_details.qte) FROM commande_details
        WHERE  commande_details.commande_id = commandes.id
        GROUP BY commande_details.commande_id) as qte_demandee,
        (SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.bl_id = bls.id
        AND bls.commande_id = commandes.id
        GROUP BY bls.commande_id) as qte_livree,commandes.id,
        CONCAT(commandes.no_commande, '/', commandes.annee) AS no_commande")
        ->where('magasin_id', Auth::user()->magasin_id)
        ->whereIn('sous_magasin_id',$userSousMagasin)->get();

        $collection =  $commandes->filter(function ($item, $key){
            return $item->qte_demandee > $item->qte_livree ;
        });

        //dd($collection);

        return response()->json([
            'error' =>false,
            'commandes' => $collection
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $tva = Tva::all();

       return view('dashboard.approvisionnement.commandes.create',compact('tva'));

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
    }

    public function storeWithStock(Request $request)
    {
        # code...
        //dd($request->all());

        $request->validate([
            'no_commande' => 'required',
            'date_commande' => 'required|date|before_or_equal:today',
            'fournisseur_id'   => 'required',
            'sous_magasin_id'   => 'required',
        ]);

            # code...
            $n = Commande::where('annee',Carbon::now()->format('Y'))
            ->where('magasin_id',Auth::user()->magasin_id)
            ->where('sous_magasin_id',$request->sous_magasin_id)->count();
            $no_commande = ($n == 0 ) ? 1 :  $n+1 ;
            $commandeData = $request->except('commandeDetails');
            $commandeData['user_id'] = Auth::id();
            $commandeData['magasin_id'] = Auth::user()->magasin_id;
            $commandeData['no_commande'] = $no_commande;
            $commandeData['annee'] = Carbon::now()->format('Y');
            $commande = Commande::create($commandeData);

              if ($request->commandeDetails) {
                # code...
                foreach ($request->commandeDetails as $stock) {

                    $produit = Produit::findOrfail($stock['produit_id']) ;
                    if ($produit->prix_unitaire != $stock['puht']) {
                    $produit->update(['prix_unitaire' => $stock['puht'] ]);
                    };
                   CommandeDetail::create([
                    'produit_id'=> $stock['produit_id'],
                    'commande_id'=> $commande->id,
                    'qte'=> $stock['qte'],
                    'puht'=> $stock['puht'],
                    'tva'=> $stock['tva'],
                    'prix_total'=> $stock['prix_total'],
                    'user_id'=> Auth::id()
                   ]);
                    # code...
                }
              }


              if ($request->imprimer) {
                # code...
                return $this->rapport($commande->id,'fun');
              }

              session()->flash('success',__('Ajouter avec succés'));
              return redirect()->back();





    }

    public function addToStock($commandeId,Request $request)
    {
        # code...
        //dd($request->stocks);
        foreach ($request->stocks as $stock) {
            $produit = Produit::findOrfail($stock['produit_id']) ;
            if ($produit->prix_unitaire != $stock['puht']) {
            $produit->update(['prix_unitaire' => $stock['puht'] ]);
            };

            $commandeDetails = CommandeDetail::where('commande_id',$commandeId)
                                                ->where('produit_id',$stock['produit_id'])
                                                ->get();
            if ( $commandeDetails->count() == 0) {
                # code...
                CommandeDetail::create([
                    'produit_id'=> $stock['produit_id'],
                    'commande_id'=> $commandeId,
                    'qte'=> $stock['qte'],
                    'puht'=> $stock['puht'],
                    'tva'=> $stock['tva'],
                    'prix_total'=> $stock['prix_total'],
                    'user_id'=> Auth::id()
                ]);
            }

         }

         return response()->json([
            'error' => false,
            'messages' => ''
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


        $fournisseurs = Fournisseur::all();
        $commande = Commande::findOrfail($id);
        //dd('kdk');
        return view('dashboard.approvisionnement.commandes.show',compact('commande','fournisseurs'));
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
            'objet' => 'required',
            'fournisseur_id'   => 'required',
            'date_commande'   => 'required',
            'tva'  => 'required',
        ]);
        $commande = Commande::findOrfail($id);
        if ($request->tva != $commande->tva) {
            # code...
            //dd($request->tva);
            $details = CommandeDetail::where('commande_id',$id)->get();
            foreach ($details as $d) {
                # code...
                $d->update([
                    'tva' => $request->tva,
                    'prix_total' => $d->qte * $d->puht * (1 + $request->tva / 100)
                ]);
            }
        }


        $commande->update($request->all());

        Bl::where('commande_id',$commande->id)->update([
            'fournisseur_id' => $commande->fournisseur_id,
            'sous_magasin_id' => $commande->sous_magasin_id,
        ]);

        $bls =  Bl::where('commande_id',$commande->id)->get();

        foreach ($bls as $key => $b) {
            # code...
            BlDetail::where('bl_id',$b->id)->update([
                'sous_magasin_id' => $b->sous_magasin_id,
            ]);
        }

        HistoriqueCommande::create([
            'commande_id' => $commande->id,
            'user_id' =>  Auth::id()
        ]);
        Session::flash('success','Modifier avec succés');
        return redirect()->back();

    }
    public function updateStock($id,Request $request)
    {
        # code...
        $stock = CommandeDetail::findOrFail($id);
        $stock->update($request->all());

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
        //
        $commande = Commande::findOrFail($id);
        if ($commande->bls->count() > 0) {
            return response(__('Non supprimer'));
        }else{
            Commande::where('annee',$commande->annee)
            ->where('no_commande','>',$commande->no_commande)->decrement('no_commande');

            CommandeDetail::where('commande_id',$id)->delete();
            $commande->delete();
            return response(__('Supprimer avec succés'));
        }
    }

    public function deleteStock($id)
    {
        //
        $stock = CommandeDetail::findOrFail($id);
        if ($stock->commande->bls->count() > 0) {
            Session::flash('error', 'Non supprimer!');
            return redirect()->back();
        }
        $stock->delete();
        //return response(__('Supprimer avec succés'));

        Session::flash('success', 'Supprimer avec succés');
        return redirect()->back();
    }

    public function rapport($id , $from=null)
    {
        # code...
         $commande = Commande::findOrFail($id);

         $commandeDetails = Commande::where('id',$commande->id)->with('commandeDetails',
                                    function($q){
                                        $q->with('produit',function($q){
                                            $q->with('uniteReglementaire');
                                        });
                                    })->first();



        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'duplicata' => '',
            'traitePar'     => 'Traité par : <strong>'.ucwords($commande->user->nom).' '.ucwords($commande->user->prenom).'</strong>',
            'name'     => $commande->no_commande.'/'.$commande->annee ,
            'date_commande'     => Carbon::parse($commande->date_commande)->format('d/m/Y') ,
            'fournisseur'     => $commande->fournisseur->nom,
            'details'     => $commandeDetails,
            'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y')))
        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/commande', array('data' => $data));

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

    public function commandeUpdateAfterPrint($id)
    {
        # code...
        $commande = Commande::findOrFail($id);
        if ($commande->imp == false) {
            # code...
            $commande->imp = true;
            $commande->update();
        }

    }


    public function getNumCommande($sousMagasinId)
    {
        # code...
        $n = Commande::where('annee',Carbon::now()->format('Y'))
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
                # code...
                    $conventions = Commande::when($request->no_convention, function ($q) use ($request) {
                        return $q->where('no_convention','like','%' . $request->no_convention . '%');
                        })->when($request->objet, function ($q) use ($request) {
                            return $q->where('objet','like','%' . $request->objet . '%');
                        })->when($request->date, function ($q) use ($request) {
                            return $q->whereDate('ods', $request->date);
                        })->when($request->fournisseur_id, function ($q) use ($request) {
                            return $q->where('fournisseur_id', $request->fournisseur_id);
                        })->when($request->magasin_id, function ($q) use ($request) {
                                return $q->where('magasin_id', $request->magasin_id);
                        })->when($request->sous_magasin_id, function ($q) use ($request) {
                                return $q->where('sous_magasin_id', $request->sous_magasin_id);
                        })->when($request->annee, function ($q) use ($request) {
                            return $q->where('annee', $request->annee);
                        })->orderBy('created_at','DESC')->get();

                    return DataTables::of($conventions)
                    ->addIndexColumn()
                    ->editColumn('no_commande', function (Commande $c) {
                        return $c->no_commande.'/'.$c->annee;
                    })
                    ->editColumn('date_commande', function (Commande $c) {
                        return Carbon::parse($c->date_commande)->format('d/m/Y');
                    })->addColumn('magasin', function (Commande $c) {
                        return $c->sousMagasin() ? $c->sousMagasin->nom:'';
                    })->addColumn('fournisseur', function (Commande $c) {
                        return $c->fournisseur->nom;
                    })
                    ->addColumn('objet', function (Commande $c) {
                        return ( Str::length($c->objet) >= 30) ? substr($c->objet,0,30).'...' : $c->objet ;
                    })->addColumn('commande_details', function (Commande $c) {
                        $details = $c->commandeDetails;
                        return view('dashboard.approvisionnement.commandes.data_table.commandeDetails', compact('details'));
                    })
                    ->addColumn('actions', 'dashboard.approvisionnement.commandes.data_table.actions')
                    ->rawColumns(['actions'])
                    ->toJson();



            }else{

                $fournisseurs = Fournisseur::all();
                $magasins = Magasin::all();
                $annees = Commande::select('annee')->groupBy('annee')->get();

                return view('dashboard.approvisionnement.commandes.autre_magasin',
                compact('fournisseurs','magasins','annees'));


            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
