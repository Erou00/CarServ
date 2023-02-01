<?php

namespace App\Http\Controllers;

use App\Models\Bl;
use App\Models\BlDetail;
use App\Models\Commande;
use App\Models\Convention;
use App\Models\Facture;
use App\Models\Fournisseur;
use App\Models\HistoriqueBl;
use App\Models\Magasin;
use App\Models\Marche;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BlController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_bl')->only(['index']);
        $this->middleware('permission:create_bl')->only(['create', 'store','blWithDetails']);
        $this->middleware('permission:update_bl')->only(['edit', 'update']);
        $this->middleware('permission:delete_bl')->only(['delete', 'bulk_delete']);

    }// end of __construct
    public function index()
    {
        //
        $bls = Bl::all();
        return view('dashboard.entrer_stock.bl.index',compact('bls'));
    }

    public function getBlofCommande(Request $request)
    {
        if ($request->ajax()) {
            # code...
            $bls = Bl::when($request->no_bl, function ($q) use ($request) {
                return $q->where('no_bl','like','%' . $request->no_bl . '%');
            })->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date', $request->date);
            })->when($request->commande_id, function ($q) use ($request) {
                return $q->where('commande_id', $request->commande_id);
            })->when($request->fournisseur_id, function ($q) use ($request) {
                return $q->where('fournisseur_id', $request->fournisseur_id);
            })->when($request->annee, function ($q) use ($request) {
                return $q->where('annee', $request->annee);
            })->where('convention_id',null)
            ->where('marche_id',null)
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

            })
            ->orderBy('created_at','DESC')
            ->orderBy('annee','DESC')->orderBy('no_entrer','DESC')->get();

           //dd($marches);
            return DataTables::of($bls)
            ->addIndexColumn()
            ->addColumn('magasin', function (Bl $bl) {

                return $bl->sousMagasin() ? $bl->sousMagasin->nom:'';
            })
            ->addColumn('fournisseur', function (Bl $bl) {
                return $bl->fournisseur->nom;
            })->addColumn('commande', function (Bl $bl) {
                return $bl->commande->no_commande.'/'.$bl->commande->annee;
            })->editColumn('date', function (Bl $bl) {
                return Carbon::parse($bl->date)->format('d/m/Y') ;
            })
            ->addColumn('bl_details', function (Bl $bl) {
                $details = $bl->blDetails;
                return view('dashboard.entrer_stock.bl.data_table.blDetails', compact('details'));
            })
            ->addColumn('actions', 'dashboard.entrer_stock.bl.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

        }

        $fournisseurs = Fournisseur::all();
        $commandes = Commande::selectRaw("commandes.id,CONCAT(commandes.no_commande, '/', commandes.annee) AS no_commande")->orderBy('date_commande','DESC')->get();
        $annees = Bl::select('annee')->groupBy('annee')->get();
        $magasins = Magasin::all();

        return view('dashboard.entrer_stock.bl.commande.index',compact('fournisseurs','magasins','commandes','annees'));
    }

    public function getBlofMarche(Request $request)
    {
        if ($request->ajax()) {
            # code...
            $bls = Bl::when($request->no_bl, function ($q) use ($request) {
                return $q->where('no_bl','like','%' . $request->no_bl . '%');
            })->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date', $request->date);
            })->when($request->marche_id, function ($q) use ($request) {
                return $q->where('marche_id', $request->marche_id);
            })->when($request->fournisseur_id, function ($q) use ($request) {
                return $q->where('fournisseur_id', $request->fournisseur_id);
            })->when($request->annee, function ($q) use ($request) {
                return $q->where('annee', $request->annee);
            })->where('convention_id',null)
            ->where('commande_id',null)
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

            })
            ->orderBy('created_at','DESC')
            ->orderBy('annee','DESC')->orderBy('no_entrer','DESC')->get();

           //dd($marches);
            return DataTables::of($bls)
            ->addIndexColumn()
            ->addColumn('magasin', function (Bl $bl) {
                return $bl->sousMagasin() ? $bl->sousMagasin->nom:'';
            })
            ->addColumn('fournisseur', function (Bl $bl) {
                return $bl->fournisseur->nom;
            })->addColumn('marche', function (Bl $bl) {
                return $bl->marche->no_marche;
            })->editColumn('date', function (Bl $bl) {
                return Carbon::parse($bl->date)->format('d/m/Y') ;
            })
            ->addColumn('bl_details', function (Bl $bl) {
                $details = $bl->blDetails;
                return view('dashboard.entrer_stock.bl.data_table.blDetails', compact('details'));
            })
            ->addColumn('actions', 'dashboard.entrer_stock.bl.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

        }

        $fournisseurs = Fournisseur::all();
        $marches = Marche::orderBy('created_at','DESC')->get();
        $annees = Bl::select('annee')->groupBy('annee')->get();

        $magasins = Magasin::all();

        return view('dashboard.entrer_stock.bl.marche.index',compact('fournisseurs','magasins','marches','annees'));
    }

    public function getBlofConvention(Request $request)
    {
        if ($request->ajax()) {
            # code...
            $bls = Bl::when($request->no_bl, function ($q) use ($request) {
                return $q->where('no_bl','like','%' . $request->no_bl . '%');
            })->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date', $request->date);
            })->when($request->convention_id, function ($q) use ($request) {
                return $q->where('convention_id', $request->convention_id);
            })->when($request->fournisseur_id, function ($q) use ($request) {
                return $q->where('fournisseur_id', $request->fournisseur_id);
            })->when($request->annee, function ($q) use ($request) {
                return $q->where('annee', $request->annee);
            })->where('commande_id',null)
            ->where('marche_id',null)
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

            })
            ->orderBy('annee','DESC')->orderBy('no_entrer','DESC')
            ->get();

           //dd($marches);
            return DataTables::of($bls)
            ->addIndexColumn()
            ->addColumn('magasin', function (Bl $bl) {
                return $bl->sousMagasin() ? $bl->sousMagasin->nom:'';
            })
            ->addColumn('fournisseur', function (Bl $bl) {
                return $bl->fournisseur->nom;
            })->addColumn('convention', function (Bl $bl) {
                return $bl->convention->no_convention;
            })->editColumn('date', function (Bl $bl) {
                return Carbon::parse($bl->date)->format('d/m/Y') ;
            })
            ->addColumn('bl_details', function (Bl $bl) {
                $details = $bl->blDetails;
                return view('dashboard.entrer_stock.bl.data_table.blDetails', compact('details'));
            })
            ->addColumn('actions', 'dashboard.entrer_stock.bl.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }

        $fournisseurs = Fournisseur::all();
        $conventions = Convention::orderBy('created_at','DESC')->get();
        $annees = Bl::select('annee')->groupBy('annee')->get();

        $magasins = Magasin::all();

        return view('dashboard.entrer_stock.bl.convention.index',compact('fournisseurs','magasins','conventions','annees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {

    }

    public function blCommande()
    {
        //

        return view('dashboard.entrer_stock.bl.commande.bl');
    }
    public function blMarche()
    {
        //
        return view('dashboard.entrer_stock.bl.marche.bl');
    }

    public function blConvention()
    {
        //


        return view('dashboard.entrer_stock.bl.convention.bl');
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

    public function blWithDetails(Request $request)
    {
        # code...
       //dd($request->all());
       $request->validate([
             'no_bl' => 'required',
            // 'retard' => 'required',
            'date'=>'required',
            'blDetails.*.qte_livree' => 'required',
        ]);


            # code...
            $n = Bl::where('annee',Carbon::now()->format('Y'))
                    ->where('magasin_id',Auth::user()->magasin_id)
                    ->where('sous_magasin_id',$request->sous_magasin_id)->count();
            $entrer = ($n == 0 ) ? 1 :  $n+1 ;
            $blData = $request->except('blDetails');
            $blData['user_id'] = Auth::id();
            $blData['no_entrer'] =$entrer;
            $blData['annee'] =Carbon::now()->format('Y');
            $bl = Bl::create($blData);

              if ($request->blDetails) {
                # code...
                foreach ($request->blDetails as $blD) {
                   $stock = Stock::where('produit_id',$blD['produit_id'])
                                   ->where('magasin_id',$bl->magasin_id)->first() ;
                   if ($stock) {
                    $stock->update(['qte' => $stock->qte + $blD['qte_livree']]);
                   }else{
                    Stock::create([
                        'produit_id'=> $blD['produit_id'],
                        'qte'=> $blD['qte_livree'],
                        'magasin_id'=> $bl->magasin_id,
                        'user_id'=> Auth::id()
                    ]);
                   }
                   BlDetail::create([
                    'produit_id'=> $blD['produit_id'],
                    'bl_id'=> $bl->id,
                    'qte'=> $blD['qte'],
                    'qte_livree'=> $blD['qte_livree'],
                    'magasin_id'=> $bl->magasin_id,
                    'sous_magasin_id'=> $bl->sous_magasin_id,
                    'user_id'=> Auth::id()

                   ]);

                }

                if ($request->imprimer) {
                    # code...
                    $this->rapport($bl->id,'fun');
                }


                Session::flash('success','Ajouter avec succés');
                return redirect()->back();


        }
    }

    public function addToBlDetails($blId,Request $request)
    {
        # code...
        $bl = Bl::findOrfail($blId);
        //dd($request->stocks);
        foreach ($request->blDetails as $bld) {
            $stock = Stock::where('produit_id',$bld['produit_id'])
                            ->where('magasin_id',$bl->magasin_id)->first() ;
            if ($stock) {
             # code...
             $stock->update(['qte' => $stock->qte + $bld['qte_livree']]);
            }else{
             Stock::create([
                 'produit_id'=> $bld['produit_id'],
                 'qte'=> $bld['qte_livree'],
                 'magasin_id'=> $bl->magasin_id,
                 'user_id'=> Auth::id()
             ]);
            }
            BlDetail::create([
             'produit_id'=> $bld['produit_id'],
             'bl_id'=> $bl->id,
             'qte'=> $bld['qte'],
             'qte_livree'=> $bld['qte_livree'],
             'magasin_id'=> $bl->magasin_id,
             'sous_magasin_id'=> $bl->sous_magasin_id,
             'user_id'=> Auth::id()
            ]);
             # code...
         }

         HistoriqueBl::create([
            'bl_id' => $bl->id,
            'user_id' =>  Auth::id()
        ]);

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
        //$fournisseurs = Fournisseur::all();
        $bl = Bl::findOrfail($id);
        $factures = Facture::all();
        //dd('kdk');
        return view('dashboard.entrer_stock.bl.show',compact('bl','factures'));
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

        $validator = Validator::make($request->all(), [
            'no_bl' => 'required',
            // 'retard' => 'required',
            'date'=>'required',
        ]);

        $bl = Bl::findOrfail($id);
        $bl->update($request->all());

        HistoriqueBl::create([
            'bl_id' => $bl->id,
            'user_id' =>  Auth::id()
        ]);

        return redirect()->back();
    }

    public function blDetailsUpdate(Request $request,$id)
    {
        # code...
        $blDetail = BlDetail::findOrfail($id);
        $stock = Stock::where('produit_id',$blDetail->produit_id)->first() ;
        $newQte = 0;
        if (  $request->qte_livree > $blDetail->qte_livree) {
            # code...
            $newQte = $stock->qte + ($request->qte_livree - $blDetail->qte_livree);
            $stock->update(['qte' => ($newQte < 0 ) ? 0  :  $newQte ]);
        } elseif($request->qte_livree < $blDetail->qte_livree  ) {
            # code...
            $newQte = $stock->qte -  ($blDetail->qte_livree - $request->qte_livree);
            $stock->update(['qte' => ($newQte < 0 ) ? 0  :  $newQte ]);
        }

        $blDetail->update($request->all());
        HistoriqueBl::create([
            'bl_id' => $blDetail->bl_id,
            'user_id' =>  Auth::id()
        ]);

        session()->flash('success',__(' '.$newQte ));
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
        $bl = Bl::findOrFail($id);

        $details =   BlDetail::where('bl_id',$bl->id)->get();
        Bl::where('annee',$bl->annee)->where('no_entrer','>',$bl->no_entrer)->decrement('no_entrer');


        foreach ($details as $d) {
            $stoks = Stock::where('produit_id',$d->produit_id)
                             ->where('magasin_id',$d->magasin_id)->get();
                foreach ($stoks as $s) {
                    $newQte = $s->qte - $d->qte_livree;
                    $s->update([
                        'qte' => ($newQte < 0 ) ? 0  : $newQte
                    ]);

                }

            $d->delete();
        }

        $bl->delete();
        return response(__('Supprimer avec succés'));
    }

    public function deleteBlDetails($id)
    {
        //
        $bld = BlDetail::findOrFail($id);

        $stoks = Stock::where('produit_id',$bld->produit_id)
                        ->where('magasin_id',$bld->magasin_id)->get();
        foreach ($stoks as $s) {
            $newQte = $s->qte - $bld->qte_livree;
            $s->update([
                'qte' => ($newQte < 0 ) ? 0  : $newQte
            ]);
        }


        $bld->delete();
        session()->flash('success',__('Supprimé avec succès'));


        return redirect()->back();
    }

    public function rapport($id,$from=null)
    {
        # code...

        $directory =  'documents';
        $storage = File::allFiles($directory);

        if($storage){
            File::delete($storage);
        }

         $bl = bl::findOrFail($id);

         $blDetails = Bl::where('id',$bl->id)->with('blDetails',
                                    function($q){
                                        $q->with('produit',function($q){
                                            $q->with('uniteReglementaire');
                                        });
                                    })->first();


        // dd($bl);

        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'duplicata' => '',
            'traitePar'     => '<strong> Traité par: </strong>'.ucwords($bl->user->nom).' '.ucwords($bl->user->prenom),
            'pour'     => $bl->marche_id ? '<strong>Marché N°:</strong>' .$bl->marche->no_marche : ($bl->commande_id ? '<strong>Bon Commande N°:</strong>' . $bl->commande->no_commande.'/'.$bl->commande->annee : '<strong>Convention N°:</strong>' .$bl->convention->no_convention),
            'date_bl'     => Carbon::parse($bl->date)->format('d/m/Y'),
            'no_bl'     => $bl->no_bl,
            'no_entrer'     => $bl->no_entrer.'/'.$bl->annee,
            'entrer'     => $bl->id,
            'fournisseur'     => $bl->fournisseur->nom,
            'details'     => $blDetails,
            'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y')))
        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/bl', array('data' => $data));

        if ($from) {
                # code...
                $pdf->save('documents/document.pdf');
                Session::flash('download.in.the.next.request','documents/document.pdf');
                return redirect()->back();

        }

        return $pdf->stream();
    }

    public function blUpdateAfterPrint($id)
    {
        # code...
        $bl = Bl::findOrFail($id);
        if ($bl->imp == false) {
            # code...
            $bl->imp = true;
            $bl->update();
        }

    }


    public function consulterAutreMagasin(Request $request)
    {

        if (Auth::user()->hasRole('master')) {
            # code...
            if ($request->ajax()) {
                $bls = Bl::when($request->no_bl, function ($q) use ($request) {
                    return $q->where('no_bl','like','%' . $request->no_bl . '%');
                })->when($request->date, function ($q) use ($request) {
                    return $q->whereDate('date', $request->date);
                })->when($request->commande_id, function ($q) use ($request) {
                    return $q->where('commande_id', $request->commande_id);
                })->when($request->fournisseur_id, function ($q) use ($request) {
                    return $q->where('fournisseur_id', $request->fournisseur_id);
                })->when($request->annee, function ($q) use ($request) {
                    return $q->where('annee', $request->annee);
                })->when($request->magasin_id, function ($q) use ($request) {
                    return $q->where('magasin_id', $request->magasin_id);
                })->when($request->sous_magasin_id, function ($q) use ($request) {
                        return $q->where('sous_magasin_id', $request->sous_magasin_id);
                })
                ->orderBy('created_at','DESC')
                ->orderBy('annee','DESC')->orderBy('no_entrer','DESC')->get();

               //dd($marches);
                return DataTables::of($bls)
                ->addIndexColumn()
                ->addColumn('fournisseur', function (Bl $bl) {
                    return $bl->fournisseur->nom;
                })->addColumn('pour', function (Bl $bl) {
                    return  $bl->marche_id ? 'Marché N°:' .$bl->marche->no_marche : ($bl->commande_id ? 'Bon Commande N°:' . $bl->commande->no_commande.'/'.$bl->commande->annee : 'Convention N°:' .$bl->convention->no_convention) ;
                })->editColumn('date', function (Bl $bl) {
                    return Carbon::parse($bl->date)->format('d/m/Y') ;
                })
                ->addColumn('bl_details', function (Bl $bl) {
                    $details = $bl->blDetails;
                    return view('dashboard.entrer_stock.bl.data_table.blDetails', compact('details'));
                })
                ->addColumn('actions', 'dashboard.entrer_stock.bl.data_table.actions')
                ->rawColumns(['actions'])
                ->toJson();



            }else{

                $fournisseurs = Fournisseur::all();
                $annees = Bl::select('annee')->groupBy('annee')->get();
                $magasins = Magasin::all();

                return view('dashboard.entrer_stock.bl.autre_magasin',compact('fournisseurs','magasins','annees'));

            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
