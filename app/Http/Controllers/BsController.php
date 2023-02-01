<?php

namespace App\Http\Controllers;

use App\Models\Bs;
use App\Models\BsDetail;
use App\Models\Demande;
use App\Models\Entite;
use App\Models\HistoriqueBs;
use App\Models\Magasin;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BsController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_bs')->only(['index']);
        $this->middleware('permission:create_bs')->only(['create', 'store', 'bsWithDetails']);
        $this->middleware('permission:update_bs')->only(['edit', 'update','bulkValidation','classement']);
        $this->middleware('permission:delete_bs')->only(['delete', 'bulk_delete']);
    } // end of __construct
    public function index(Request $request)
    {
        //
        // $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
        //     return $item->id;
        // })->toArray();
        // dd($userSousMagasin );
        if ($request->ajax()) {
            # code...
            $bls = Bs::when($request->no_bl, function ($q) use ($request) {
                return $q->where('no_bl', intval(substr($request->no_bl, 0, strpos($request->no_bl, "/") + 1)))
                    ->where('annee', 'like', '%' . substr($request->no_bl, strpos($request->no_bl, "/") + 1)  . '%');
            })->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date', $request->date);
            })->when($request->commande_id, function ($q) use ($request) {
                return $q->where('demande_id', $request->commande_id);
            })->when($request->entite_id, function ($q) use ($request) {
                return $q->where('entite_id', $request->entite_id);
            })->when($request->sortie, function ($q) use ($request) {
                $q->where('sortie', $request->sortie);
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


            })
                ->orderBy('sortie', 'ASC')
                ->orderBy('annee', 'DESC')
                ->orderBy('no_bl', 'DESC')



                ->get();

            //dd($marches);
            return DataTables::of($bls)
                ->addIndexColumn()
                ->editColumn('no_bl', function (Bs $bl) {

                    return $bl->no_bl . '/' . $bl->annee;
                })->editColumn('date', function (Bs $bl) {
                    return Carbon::parse($bl->date)->format('d/m/Y');
                })->addColumn('record_select','dashboard.sortie_stock.bs.data_table.record_select')

                ->addColumn('entite', function (Bs $bl) {
                    return ($bl->entite) ? $bl->entite->nom : '';
                })->addColumn('commande', function (Bs $bl) {
                    return $bl->demande->no_commande . '/' . $bl->demande->annee;
                })->addColumn('magasin', function (Bs $bl) {
                    return ($bl->sousMagasin()) ? $bl->sousMagasin->nom : '';
                })
                ->addColumn('validation', 'dashboard.sortie_stock.bs.data_table.validation')
                ->addColumn('bl_details', function (Bs $bl) {
                    $details = $bl->bsDetails;
                    return view('dashboard.sortie_stock.bs.data_table.bsDetails', compact('details'));
                })
                ->addColumn('actions', 'dashboard.sortie_stock.bs.data_table.actions')
                ->rawColumns(['record_select','actions', 'validation'])
                ->setRowClass(function (Bs $bs) {

                    return $bs->sortie  == 'annulation' ? '' : '';
                })
                ->toJson();
        }

        $entites = Entite::all();
        $commandes = Demande::orderBy('created_at', 'DESC')->get();
        $annees = Bs::select('annee')->groupBy('annee')->get();
        $magasins = Magasin::all();


        return view('dashboard.sortie_stock.bs.index', compact('entites', 'magasins', 'commandes', 'annees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        // dd($bs);
        return view('dashboard.sortie_stock.bs.create');
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

    public function bsWithDetails(Request $request)
    {
        # code...
        // dd($request->all());
        $request->validate([
            'demande_id' => 'required',
            'date' => 'required',
            'bsDetails..*.magasin_id' => 'required',
            'bsDetails.*.qte_donnee' => 'required',
        ]);

        $n = Bs::where('annee', Carbon::now()->format('Y'))
            ->where('magasin_id', Auth::user()->magasin_id)
            ->where('sous_magasin_id', $request->sous_magasin_id)->count();
        $n_bl = ($n == 0) ? 1 :  $n + 1;
        $bsdata = $request->except(['bsDetails', 'no_bl']);
        $bsdata['user_id'] = Auth::id();
        $bsdata['magasin_id'] = Auth::user()->magasin_id;
        $bsdata['no_bl'] = $n_bl;
        $bsdata['annee'] = Carbon::now()->format('Y');
        $bsdata['sortie'] = 'preparation';
        $bs = Bs::create($bsdata);

        if ($request->bsDetails) {
            # code...
            foreach ($request->bsDetails as $bsd) {
                BsDetail::create([
                    'produit_id' => $bsd['produit_id'],
                    'bs_id' => $bs->id,
                    'qte_demandee' => $bsd['qte_demandee'],
                    'qte_donnee' => $bsd['qte_donnee'],
                    'magasin_id' =>  $bs->magasin_id,
                    'sous_magasin_id' =>  $bs->sous_magasin_id,
                    'user_id' => Auth::id()

                ]);
            }

            if ($request->imprimer) {
                # code...
                $this->rapport($bs->id, 'fun', 'non');
            }

            Session::flash('success', 'Ajouter avec succés');
            return redirect()->back();
        }
    }

    public function fetchBs()
    {
        # code...entites

        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item) {
            return $item->id;
        })->toArray();
        return response()->json([
            'error' => false,
            'bs' => Bs::select(
                "*",
                DB::raw("CONCAT(no_bl, '/',annee) AS name"),
                DB::raw('DATE_FORMAT(bs.date, "%d/%m/%Y") as date')
            )
                ->where('magasin_id', Auth::user()->magasin_id)
                ->whereIn('sous_magasin_id', $userSousMagasin)
                ->doesntHave('be')
                ->with('entite')
                ->orderBy('annee', 'DESC')
                ->orderBy('no_bl', 'DESC')->get(),
        ], 200);
    }

    public function mbs()
    {
        # code...
        return view('dashboard.sortie_stock.mbs');
    }

    public function addToBsDetails($id, Request $request)
    {
        # code...
        //dd($request->stocks);
        foreach ($request->bsDetails as $bsd) {
            BsDetail::create([
                'produit_id' => $bsd['produit_id'],
                'bs_id' => $id,
                'qte_demandee' => $bsd['qte_demandee'],
                'qte_donnee' => $bsd['qte_donnee'],
                'magasin_id' => $bsd['magasin_id'],
                'user_id' => Auth::id()
            ]);
            # code...
        }

        return response()->json([
            'error' => false,
            'messages' => ''
        ], 200);
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
        $bl = Bs::findOrfail($id);
        //dd('kdk');
        return view('dashboard.sortie_stock.bs.show', compact('bl'));
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
            'sortie' => 'required'
        ]);

        $bs = Bs::findOrfail($id);
        if ($request->sortie == 'validation') {
            # code...
            foreach ($bs->bsDetails as $detail) {
                # code...
                $stock = Stock::where('produit_id', $detail->produit_id)
                    ->where('magasin_id', $detail->magasin_id)->first();
                if ($stock) {
                    # code...
                    $newQte = $stock->qte - $detail->qte_donnee;
                    $stock->qte = ($newQte > 0) ? $newQte  : 0;
                    $stock->update();
                }
            }
        }elseif ($request->sortie == 'annulation') {
                if (auth()->user()->hasPermission('annulation_bs')) {
                    # code...
                    if ($bs->sortie == 'validation') {
                        # code...
                        foreach ($bs->bsDetails as $detail) {
                            # code...
                            $stock = Stock::where('produit_id', $detail->produit_id)
                                ->where('magasin_id', $detail->magasin_id)->first();
                            if ($stock) {
                                # code...
                                $newQte = $stock->qte + $detail->qte_donnee;
                                $stock->qte = ($newQte > 0) ? $newQte  : 0;
                                $stock->update();
                            }
                        }
                    }
                }
        }
        HistoriqueBs::create([
            'bs_id' => $bs->id,
            'user_id' =>  Auth::id()
        ]);
        $bs->update($request->all());



        return redirect()->back();
    }

    public function classement($id, Request $request)
    {
        # code...
        $messages = '';

        $bs = Bs::findOrfail($id);
        if ($request->sortie != 'preparation') {
            # code...
            if ($request->sortie == 'validation' && $bs->sortie == 'preparation') {
                # code...
                foreach ($bs->bsDetails as $detail) {
                    # code...
                    $stock = Stock::where('produit_id', $detail->produit_id)
                        ->where('magasin_id', $detail->magasin_id)->first();
                    if ($stock) {
                        # code...
                        $newQte = $stock->qte - $detail->qte_donnee;
                        $stock->qte = ($newQte > 0) ? $newQte  : 0;
                        $stock->update();
                    }
                }
                $bs->update([
                    'sortie' => 'validation'
                ]);
                $messages = "BL N°" . $bs->no_bl . "/" . $bs->annee . " a ete validée avec succes";
            } elseif ($request->sortie == 'annulation') {
                if (auth()->user()->hasPermission('annulation_bs')) {
                    # code...
                    if ($bs->sortie == 'validation') {
                        # code...
                        foreach ($bs->bsDetails as $detail) {
                            # code...
                            $stock = Stock::where('produit_id', $detail->produit_id)
                                ->where('magasin_id', $detail->magasin_id)->first();
                            if ($stock) {
                                # code...
                                $newQte = $stock->qte + $detail->qte_donnee;
                                $stock->qte = ($newQte > 0) ? $newQte  : 0;
                                $stock->update();
                            }
                        }

                        $bs->update([
                            'sortie' => 'annulation'
                        ]);
                        $messages = "BL N°" . $bs->no_bl . "/" . $bs->annee . " a ete annulee avec succes";
                    } else {
                        $messages = "BL N°" . $bs->no_bl . "/" . $bs->annee . " dans etat de preparation vous pouvez le supprimer";
                    }
                }else{
                    $messages = "Vous n avez pas la permision d'annuler les B.L";

                }

            }
            HistoriqueBs::create([
                'bs_id' => $bs->id,
                'user_id' =>  Auth::id()
            ]);

        }


        return response(__($messages));
    }

    public function BsDetailsUpdate(Request $request, $id)
    {
        # code...
        $bsdetail = BsDetail::findOrfail($id);
        if ($bsdetail->bs->sortie == 'validation' || $bsdetail->bs->sortie == 'annulation' ) {
            # code...
            session()->flash('error', 'Déjà traitée');
            return redirect()->back();
        }
        $bsdetail->update($request->all());

        return redirect()->back();
    }

    public function bulkValidation()
    {
        # code...
        if (auth()->user()->hasPermission('update_bs')) {
            foreach (json_decode(request()->record_ids) as $recordId) {
                $bs = Bs::findOrfail($recordId);

                if ($bs->sortie == 'preparation') {
                    # code...
                    foreach ($bs->bsDetails as $detail) {
                        # code...
                        $stock = Stock::where('produit_id', $detail->produit_id)
                            ->where('magasin_id', $detail->magasin_id)->first();
                        if ($stock) {
                            # code...
                            $newQte = $stock->qte - $detail->qte_donnee;
                            $stock->qte = ($newQte > 0) ? $newQte  : 0;
                            $stock->update();
                        }
                    }
                    $bs->update([
                        'sortie' => 'validation'
                    ]);
                }

            }

            session()->flash('success', __('Classées avec succes'));
            return response(__('Classées avec succes'));
        }
        else{
            session()->flash('success', __("Vous n avez pas la permision de classer les B.L"));
        return response(__("Vous n avez pas la permision de classer les B.L"));
        }

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
        $bs = Bs::findOrFail($id);

        if ($bs->sortie == 'validation' || $bs->sortie == 'annulation') {
            # code...
            return response(__('Déjà traitée'));
        }

        Bs::where('annee', $bs->annee)->where('no_entrer', '>', $bs->no_entrer)->decrement('no_bl');
        BsDetail::where('bs_id', $bs->id)->delete();
        $bs->delete();
        session()->flash('success', __('Supprimé avec succès'));
        return redirect()->route('bs.index');
    }

    public function deleteBsDetails($id)
    {
        //
        $bsd = BsDetail::findOrFail($id);

        if ($bsd->bs->sortie == 'validation' || $bsd->bs->sortie == 'annulation') {
            # code...
            session()->flash('error', __('La Quantité déja Sortie'));
            return redirect()->back();
        }

        $bsd->delete();
        session()->flash('success', __('Supprimé avec succès'));
        return redirect()->back();
    }

    public function rapport($id, $from = null, $duplicata)
    {
        # code...
        // dd(pathinfo(public_path()));

        $directory =  'documents';
        $storage = File::allFiles($directory);

        if ($storage) {
            File::delete($storage);
        }

        $bl = bs::findOrFail($id);

        $blDetails = Bs::where('id', $bl->id)->with(
            'bsDetails',
            function ($q) use ($bl) {
                $q->join('produits', 'bs_details.produit_id', '=', 'produits.id')
                    ->select(
                        'bs_details.*',
                        DB::raw("COALESCE((SELECT   SUM(bs_details.qte_donnee)
                                                FROM bs_details,bs
                                                WHERE bs_details.produit_id = produits.id
                                                AND bs_details.bs_id = bs.id
                                                AND (bs.sortie like 'validation' OR bs.sortie like 'preparation')
                                                AND bs.entite_id like " . $bl->entite_id . "
                                                AND YEAR(bs.date) = " . Carbon::now()->format('Y') . "
                                                GROUP BY bs_details.produit_id),0) as sum_qte_donnee")
                    )
                    ->with('produit', function ($q) {
                        $q->with('uniteReglementaire');
                    })->with('magasin');
            }
        )->first();



        // dd($blDetails->bsDetails);
        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'duplicata' => ($duplicata == 'non') ? '' : 'duplicata',
            'traitePar'     => '<strong>Traité par : </strong>'.ucwords($bl->user->nom).' '.ucwords($bl->user->prenom),
            'magasin'     => '<strong>Magasin : </strong>'.$bl->magasin->nom,
            'sous_magasin'     => '<strong>Sous Magasin : </strong><span class="small">'.ucwords($bl->sousMagasin->nom).'</span>',
            'date_bl'     => Carbon::parse($bl->date)->format('d/m/Y'),
            'no_sortie'     => $bl->no_bl . '/' . $bl->annee,
            'pour'  =>  $bl->demande->no_commande . '/' . $bl->demande->annee,
            'sortie' => $bl->id,
            'entite'     => $bl->entite->nom,
            'details'     => ($blDetails) ? $blDetails->bsDetails : [],
            'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y'))),
            'currentYear' => Carbon::now()->format('Y')
        ];



        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/bs', array('data' => $data));


        if ($bl->imp == false && $bl->demande->entite->email != "") {
            $email = $bl->demande->entite->email;
            $n_demande =  $bl->demande->no_commande.'/'.$bl->demande->annee;
            $this->sendEmail($data,$n_demande,$email);
        }
        $this->blUpdateAfterPrint($bl->id);



        if ($from) {
            # code...
            $pdf->save('documents/document.pdf');
            Session::flash('download.in.the.next.request', 'documents/document.pdf');
            $this->blUpdateAfterPrint($bl->id);
            return redirect()->back();
        }

        return $pdf->stream();
    }

    public function sendEmail($data,$n_demande,$email)
    {
        # code...

        // try {
        $data['duplicata'] = 'duplicata';
        $data['n_demande'] = $n_demande;

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/bs', array('data' => $data));
        Mail::send('dashboard.sortie_stock.bs.email', $data, function($message)use($pdf,$email,$n_demande,$data) {

            $message->to($email)
                    ->subject("Votre demande n° : ".$n_demande." a étè traitée")
                    ->attachData($pdf->output(), "B.L N°: ".$data['no_sortie'].".pdf");

        });

        // } catch (\Exception $exception) {
        //         //throw $th;
        //     session()->flash('success',"error d'envoie d'email");

        // return redirect()->back();
        //     }
    }

    public function blUpdateAfterPrint($id)
    {
        # code...
        $bl = Bs::findOrFail($id);
        if ($bl->imp == false) {
            # code...
            $bl->imp = true;
            $bl->update();
        }
    }

    public function getNumCommande($sousMagasinId)
    {
        # code...
        $n = Bs::where('annee', Carbon::now()->format('Y'))
            ->where('magasin_id', Auth::user()->magasin_id)
            ->where('sous_magasin_id', $sousMagasinId)->count();
        $no_commande = ($n == 0) ? 1 :  $n + 1;

        return response()->json([
            'error' => false,
            'no_commande' => $no_commande . '/' . Carbon::now()->format('Y')
        ]);
    }



    public function consulterAutreMagasin(Request $request)
    {

        if (Auth::user()->hasRole('master')) {
            # code...
            if ($request->ajax()) {
                $bls = Bs::when($request->no_bl, function ($q) use ($request) {
                    return $q->where('no_bl', intval(substr($request->no_bl, 0, strpos($request->no_bl, "/") + 1)))
                        ->where('annee', 'like', '%' . substr($request->no_bl, strpos($request->no_bl, "/") + 1)  . '%');
                })->when($request->date, function ($q) use ($request) {
                    return $q->whereDate('date', $request->date);
                })->when($request->commande_id, function ($q) use ($request) {
                    return $q->where('demande_id', $request->commande_id);
                })->when($request->entite_id, function ($q) use ($request) {
                    return $q->where('entite_id', $request->entite_id);
                })->when($request->sortie, function ($q) use ($request) {
                    $q->where('sortie', $request->sortie);
                })->when($request->annee, function ($q) use ($request) {
                    return $q->where('annee', $request->annee);
                })->when($request->magasin_id, function ($q) use ($request) {
                    return $q->where('magasin_id', $request->magasin_id);
                })->when($request->sous_magasin_id, function ($q) use ($request) {
                        return $q->where('sous_magasin_id', $request->sous_magasin_id);
                })
                    ->orderBy('sortie', 'ASC')
                    ->orderBy('annee', 'DESC')
                    ->orderBy('no_bl', 'DESC')



                    ->get();

                //dd($marches);
                return DataTables::of($bls)
                    ->addIndexColumn()
                    ->editColumn('no_bl', function (Bs $bl) {

                        return $bl->no_bl . '/' . $bl->annee;
                    })->editColumn('date', function (Bs $bl) {
                        return Carbon::parse($bl->date)->format('d/m/Y');
                    })

                    ->addColumn('entite', function (Bs $bl) {
                        return ($bl->entite) ? $bl->entite->nom : '';
                    })->addColumn('commande', function (Bs $bl) {
                        return $bl->demande->no_commande . '/' . $bl->demande->annee;
                    })->editColumn('sortie', function (Bs $bl) {
                        return  ($bl->sortie != "validation") ? $bl->sortie : 'classée';
                    })->addColumn('magasin', function (Bs $bl) {
                        return ($bl->sousMagasin()) ? $bl->sousMagasin->nom : '';
                    })
                    ->addColumn('bl_details', function (Bs $bl) {
                        $details = $bl->bsDetails;
                        return view('dashboard.sortie_stock.bs.data_table.bsDetails', compact('details'));
                    })

                    ->rawColumns(['record_select','actions', 'validation'])
                    ->setRowClass(function (Bs $bs) {

                        return $bs->sortie  == 'annulation' ? '' : '';
                    })
                    ->toJson();
            }else{

                $entites = Entite::all();
                $commandes = Demande::orderBy('created_at', 'DESC')->get();
                $annees = Bs::select('annee')->groupBy('annee')->get();
                $magasins = Magasin::all();


                return view('dashboard.sortie_stock.bs.autre_magasin', compact('entites', 'magasins', 'commandes', 'annees'));

            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
