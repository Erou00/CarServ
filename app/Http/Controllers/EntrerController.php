<?php

namespace App\Http\Controllers;

use App\Models\Entrer;
use App\Models\EntrerDetail;
use App\Models\Fournisseur;
use App\Models\HistoriqueEntrer;
use App\Models\Magasin;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Tva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EntrerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            # code...
                $entrers = Entrer::when($request->no_entrer, function ($q) use ($request) {
                    return $q->where('no_bl','like','%' . $request->no_entrer . '%');
                })->when($request->objet, function ($q) use ($request) {
                    return $q->where('objet','like','%' . $request->objet . '%');
                })->when($request->date, function ($q) use ($request) {
                    return $q->whereDate('date', $request->date);
                })->when($request->fournisseur_id, function ($q) use ($request) {
                    return $q->where('fournisseur_id', $request->fournisseur_id);
                })->when($request->annee, function ($q) use ($request) {
                    return $q->where('annee', $request->annee);
                })->where(function($query) use ($request) {
                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->magasin_id, function ($q) use ($request) {
                            return $q->where('magasin_id', $request->magasin_id);
                        });
                    }else{
                        $query->where('magasin_id',Auth::user()->magasin_id);
                    }
                })->where(function($query) use ($request) {

                    if (Auth::user()->hasRole('master')) {
                        $query->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('sous_magasin_id', $request->sous_magasin_id);
                        });
                    }else{
                        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
                            return $item->id;
                        })->toArray();
                        $query->whereIn('sous_magasin_id',$userSousMagasin);
                    }

                })->orderBy('annee','DESC')->orderBy('no_entrer','DESC')->get();

            //dd($marches);
                return DataTables::of($entrers)
                ->addIndexColumn()
                ->addColumn('magasin', function (Entrer $c) {
                    return $c->sousMagasin() ? $c->sousMagasin->nom:'';
                })
                ->addColumn('fournisseur', function (Entrer $c) {
                    return $c->fournisseur->nom;
                })
                ->addColumn('entrer_details', function (Entrer $c) {
                    $details = $c->entrerDetails;
                    return view('dashboard.entrer_stock.autre.data_table.autreDetails', compact('details'));;
                })->editColumn('date', function (Entrer $c) {
                    return Carbon::parse($c->date)->format('d/m/Y') ;
                })
                ->addColumn('actions', 'dashboard.entrer_stock.autre.data_table.actions')
                ->rawColumns(['actions'])
                ->toJson();

        }
        $fournisseurs = Fournisseur::all();
        $annees = Entrer::select('annee')->groupBy('annee')->get();
        $magasins = Magasin::all();

        return view('dashboard.entrer_stock.autre.index',compact('magasins','fournisseurs','annees'));
    }

    public function allentrers()
    {
        # code...
        $entrers = DB::table('entrers')
        ->selectRaw("(SELECT SUM(entrer_details.qte) FROM entrer_details
        WHERE  entrer_details.entrer_id = entrers.id
        GROUP BY entrer_details.entrer_id) as qte_demandee,
        (SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.bl_id = bls.id
        AND bls.entrer_id = entrers.id
        GROUP BY bls.entrer_id) as qte_livree,
        entrers.*")
        ->get();


        $collection =  $entrers->filter(function ($item, $key){
            return $item->qte_demandee > $item->qte_livree ;
        });

        //dd($collection);

        return response()->json([
            'error' =>false,
            'entrers' => $collection
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

        return view('dashboard.entrer_stock.autre.create',compact('tva'));
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
            'no_bl' => 'required',
            'date' => 'required',
            'sous_magasin_id' => 'required',
            'fournisseur_id'   => 'required',
        ]);

            # code...
            $n = Entrer::where('annee',Carbon::now()->format('Y'))
                        ->where('magasin_id',Auth::user()->magasin_id)
                        ->where('sous_magasin_id',$request->sous_magasin_id)->count();
            $entrer = ($n == 0 ) ? 1 :  $n+1 ;
            $entrerData = $request->except('entrerDetails');
            $entrerData['user_id'] = Auth::id();
            $entrerData['magasin_id'] = Auth::user()->magasin_id;
            $entrerData['no_entrer'] = $entrer;
            $entrerData['annee'] = Carbon::now()->format('Y');
            $entrer = Entrer::create($entrerData);

                foreach ($request->entrerDetails as $stock) {

                    $produit = Produit::findOrfail($stock['produit_id']) ;
                    if ($produit->prix_unitaire != $stock['puht']) {
                    $produit->update(['prix_unitaire' => $stock['puht'] ]);
                    };

                    $verifyStock = Stock::where('produit_id',$stock['produit_id'])
                                          ->where('magasin_id',$entrer->magasin_id)->first() ;
                    if ($verifyStock) {
                     $verifyStock->update(['qte' => $verifyStock->qte + $stock['qte']]);
                    }else{
                     Stock::create([
                         'produit_id'=> $stock['produit_id'],
                         'qte'=> $stock['qte'],
                         'magasin_id'=> $entrer->magasin_id,
                         'sous_magasin_id'=> $entrer->sous_magasin_id,
                         'user_id'=> Auth::id()
                     ]);
                    }

                   EntrerDetail::create([
                    'produit_id'=> $stock['produit_id'],
                    'entrer_id'=> $entrer->id,
                    'qte'=> $stock['qte'],
                    'puht'=> $stock['puht'],
                    'prix_total'=> $stock['prix_total'],
                    'tva'=> $stock['tva'],
                    'magasin_id'=> $entrer->magasin_id,
                    'sous_magasin_id'=> $entrer->sous_magasin_id,
                    'user_id'=> Auth::id()
                   ]);
                    # code...
                }

                if ($request->imprimer) {
                    # code...
                    $this->rapport($entrer->id,'fun');
                }

            session()->flash('success',__('Ajouter avec succés'));
            return redirect()->back();

    }

    public function addToStock($entrerId,Request $request)
    {
        # code...
        //dd($request->stocks);
        $entrer = Entrer::findOrFail($entrerId);
        foreach ($request->stocks as $stock) {

            $entrerDetails = EntrerDetail::where('entrer_id',$entrerId)
                                                ->where('produit_id',$stock['produit_id'])
                                                ->get();
            if ($entrerDetails->count() == 0) {
                # code...
                        $produit = Produit::findOrfail($stock['produit_id']) ;
                        if ($produit->prix_unitaire != $stock['puht']) {
                        $produit->update(['prix_unitaire' => $stock['puht'] ]);
                        };

                        $verifyStock = Stock::where('produit_id',$stock['produit_id'])
                                              ->where('magasin_id',$entrer->magasin_id)->first() ;
                        if ($verifyStock) {
                            $verifyStock->update(['qte' => $verifyStock->qte + $stock['qte']]);
                        }else{
                            Stock::create([
                                'produit_id'=> $stock['produit_id'],
                                'qte'=> $stock['qte'],
                                'magasin_id'=> $entrer->magasin_id,
                                'user_id'=> Auth::id()
                            ]);
                        }
                        EntrerDetail::create([
                            'produit_id'=> $stock['produit_id'],
                            'entrer_id'=> $entrerId,
                            'qte'=> $stock['qte'],
                            'puht'=> $stock['puht'],
                            'tva'=> $stock['tva'],
                            'prix_total'=> $stock['prix_total'],
                            'magasin_id'=> $entrer->magasin_id,
                            'sous_magasin_id'=> $entrer->sous_magasin_id,
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
        $entrer = Entrer::findOrfail($id);
        //dd('kdk');
        return view('dashboard.entrer_stock.autre.show',compact('entrer','fournisseurs'));
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
            'no_bl' => 'required',
            'date' => 'required',
            'fournisseur_id' => 'required',
        ]);
        $entrer = Entrer::findOrfail($id);

        if ($request->tva != $entrer->tva) {
            # code...
            //dd($request->tva);
            $details = EntrerDetail::where('entrer_id',$id)->get();
            foreach ($details as $d) {
                # code...
                $d->update([
                    'tva' => $request->tva,
                    'prix_total' => $d->qte * $d->puht * (1 + $request->tva / 100)
                ]);
            }
        }
        $entrer->update($request->all());

        HistoriqueEntrer::create([
            'entrer_id' => $entrer->id,
            'user_id' =>  Auth::id()
        ]);
        return redirect()->back();

    }
    public function updateAutreDetails($id,Request $request)
    {
        # code...
        $entrerDetails = EntrerDetail::findOrFail($id);
        $newQte = 0;
        $stock = Stock::where('produit_id',$entrerDetails->produit_id)
                        ->where('magasin_id',$entrerDetails->magasin_id)->first() ;
        if (  $request->qte >  $entrerDetails->qte) {
            # code...
            $newQte = $stock->qte + ($request->qte - $entrerDetails->qte);
            $stock->update(['qte' => ($newQte < 0 ) ? 0  :  $newQte ]);
        } elseif($request->qte < $entrerDetails->qte ) {
            # code...
            $newQte = $stock->qte -  ($entrerDetails->qte - $request->qte);
            $stock->update(['qte' => ($newQte < 0 ) ? 0  :  $newQte]);
        }

        $entrerDetails->update($request->all());

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
        $entrer = Entrer::findOrFail($id);
        // 2022-12-29 11:43:14
        Entrer::where('annee',$entrer->annee)->where('no_entrer','>',$entrer->no_entrer)->decrement('no_entrer');

        $details =   EntrerDetail::where('entrer_id',$entrer->id)->get();

        if ( $details) {
            # code...
            foreach ($details as $d) {
                $stoks = Stock::where('produit_id',$d->produit_id)->get();
                    foreach ($stoks as $s) {
                        $newQte = $s->qte - $d->qte;
                        $s->update([
                            'qte' => ($newQte < 0 ) ? 0  : $newQte
                        ]);
                    }

                $d->delete();
            }
        }


        $entrer->delete();
        session()->flash('success',__('Supprimé avec succès'));


        return response(__('Supprimer avec succés'));
    }

    public function deleteStock($id)
    {
        //
        $stock = EntrerDetail::findOrFail($id);

        $verifyStocks = Stock::where('produit_id',$stock->produit_id)
                         ->where('magasin_id',$stock->magasin_id)->get();
        foreach ($verifyStocks as $s) {
            $newQte = $s->qte - $stock->qte;
            $s->update([
                'qte' => ($newQte < 0 ) ? 0  : $newQte
            ]);
        }

        $stock->delete();
        session()->flash('success',__('Supprimé avec succès'));


        return redirect()->back();
    }

    public function rapport($id,$from=null)
    {
        # code...
         $entrer = Entrer::findOrFail($id);

         $EntrerDetail = Entrer::where('id',$entrer->id)->with('EntrerDetails',
                                    function($q){
                                        $q->with('produit',function($q){
                                            $q->with('uniteReglementaire');
                                        });
                                    })->first();



        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'duplicata' => '',
            'traitePar'     => 'Traité par : <strong>'.ucwords($entrer->user->nom).' '.ucwords($entrer->user->prenom).'</strong>',
            'no_bl'=> $entrer->no_bl,
            'date_bl'=> Carbon::parse($entrer->date)->format('d/m/Y'),
            'no_entrer'     => $entrer->no_entrer.'/'.$entrer->annee ,
            'date_entrer'     => $entrer->date_entrer,
            'fournisseur'     => $entrer->fournisseur->nom,
            'details'     => $EntrerDetail,
            'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y')))
        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/entrer', array('data' => $data));

        if ($from) {
            # code...
            $pdf->save('documents/document.pdf');
            Session::flash('download.in.the.next.request','documents/document.pdf');
            return redirect()->back();
        }

        return $pdf->stream();
    }

    public function entrerUpdateAfterPrint($id)
    {
        # code...
        $entrer = Entrer::findOrFail($id);
        if ($entrer->imp == false) {
            # code...
            $entrer->imp = true;
            $entrer->update();
        }

    }

    public function consulterAutreMagasin(Request $request)
    {

        if (Auth::user()->hasRole('master')) {
            # code...
            if ($request->ajax()) {
                $entrers = Entrer::when($request->no_entrer, function ($q) use ($request) {
                    return $q->where('no_bl','like','%' . $request->no_entrer . '%');
                })->when($request->objet, function ($q) use ($request) {
                    return $q->where('objet','like','%' . $request->objet . '%');
                })->when($request->date, function ($q) use ($request) {
                    return $q->whereDate('date', $request->date);
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
               return DataTables::of($entrers)
               ->addIndexColumn()
               ->addColumn('fournisseur', function (Entrer $c) {
                   return $c->fournisseur->nom;
               })
               ->addColumn('entrer_details', function (Entrer $c) {
                   $details = $c->entrerDetails;
                   return view('dashboard.entrer_stock.autre.data_table.autreDetails', compact('details'));;
               })->editColumn('date', function (Entrer $c) {
                   return Carbon::parse($c->date)->format('d/m/Y') ;
               })
               ->addColumn('actions', 'dashboard.entrer_stock.autre.data_table.actions')
               ->rawColumns(['actions'])
               ->toJson();



            }else{

                $fournisseurs = Fournisseur::all();
                $annees = Entrer::select('annee')->groupBy('annee')->get();
                $magasins = Magasin::all();

                return view('dashboard.entrer_stock.autre.autre_magasin',compact('fournisseurs','magasins','annees'));

            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
