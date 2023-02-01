<?php

namespace App\Http\Controllers;

use App\Models\Bl;
use App\Models\BlDetail;
use App\Models\Fournisseur;
use App\Models\HistoriqueMarche;
use App\Models\Magasin;
use App\Models\Marche;
use App\Models\MarcheDetail;
use App\Models\Produit;
use App\Models\Tva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use \PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class MarcheController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_marches')->only(['index']);
        $this->middleware('permission:create_marches')->only(['create', 'store','storeWithStock']);
        $this->middleware('permission:update_marches')->only(['edit', 'update']);
        $this->middleware('permission:delete_marches')->only(['delete', 'bulk_delete']);

    }// end of __construct
    public function index()
    {
        //
        $fournisseurs = Fournisseur::all();
        $magasins = Magasin::all();

        return view('dashboard.approvisionnement.marches.index',compact('fournisseurs','magasins'));
    }

    public function allMarches()
    {
        # code...
        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
            return $item->id;
        })->toArray();


        $marches = DB::table('marches')
        ->selectRaw("(SELECT SUM(marche_details.qte) FROM marche_details
        WHERE  marche_details.marche_id = marches.id
        GROUP BY marche_details.marche_id) as qte_demandee,
        (SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.bl_id = bls.id
        AND bls.marche_id = marches.id
        GROUP BY bls.marche_id) as qte_livree,
        marches.*")
        ->where('magasin_id', Auth::user()->magasin_id)
        ->whereIn('sous_magasin_id',$userSousMagasin)->get();

        $collection =  $marches->filter(function ($item, $key){
            return $item->qte_demandee > $item->qte_livree ;
        });


        return response()->json([
            'error' =>false,
            'marches' => $collection
        ]);
    }


    public function data(Request $request)
    {
        $marches = Marche::when($request->no_marche, function ($q) use ($request) {
            return $q->where('no_marche','like','%' . $request->no_marche . '%');
        })->when($request->objet, function ($q) use ($request) {
            return $q->where('objet','like','%' . $request->objet . '%');
        })->when($request->date, function ($q) use ($request) {
            return $q->whereDate('ods', $request->date);
        })->when($request->fournisseur_id, function ($q) use ($request) {
            return $q->where('fournisseur_id', $request->fournisseur_id);
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

        })->orderBy('created_at','DESC')->get();


       //dd($marches);
        return DataTables::of($marches)
        ->addIndexColumn()
        ->addColumn('magasin', function (Marche $m) {

            return $m->sousMagasin() ? $m->sousMagasin->nom:'';
        })
        ->addColumn('fournisseur', function (Marche $m) {
            return $m->fournisseur->nom;
        })
        ->addColumn('objet', function (Marche $m) {
            return ( Str::length($m->objet) >= 30) ? substr($m->objet,0,30).'...' : $m->objet ;
        })->editColumn('ods',function(Marche $m){
            return Carbon::parse($m->ods)->format('d/m/Y');
        })->addColumn('marche_details', function (Marche $m) {
            $details = $m->marcheDetails;
            return view('dashboard.approvisionnement.marches.data_table.marcheDetails', compact('details'));;
        })
        ->addColumn('actions', 'dashboard.approvisionnement.marches.data_table.actions')
        ->rawColumns(['actions'])
        ->toJson();

    }// end of data

    public function create()
    {
        //
        $tva = Tva::all();

       //dd($marches);
        return view('dashboard.approvisionnement.marches.create',compact('tva'));
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
        $request->validate([
            'no_marche' => 'required',
            'objet' => 'required',
            'ods' => 'required',
            'fournisseur_id'   => 'required',
            'delais_execution'   => 'required',
            'type'  => 'required'

        ]);

            # code...
            $marcheData = $request->except('marcheDetails');
            $marcheData['user_id'] = Auth::id();
            $marcheData['magasin_id'] = Auth::user()->magasin_id;
            $marche = Marche::create($marcheData);


              if ($request->marcheDetails) {
                # code...
                foreach ($request->marcheDetails as $stock) {

                    $produit = Produit::findOrfail($stock['produit_id']) ;
                    if ($produit->prix_unitaire != $stock['puht']) {
                    $produit->update(['prix_unitaire' => $stock['puht'] ]);
                    };

                   MarcheDetail::create([
                    'produit_id'=> $stock['produit_id'],
                    'marche_id'=> $marche->id,
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
                return $this->rapport($marche->id,'fun');
              }

              Session::flash('success', 'Ajouter avec succés');
              return redirect()->back();


    //
     }

    public function addToStock($marcheId,Request $request)
    {
        # code...
        foreach ($request->marcheDetails as $stock) {
            $produit = Produit::findOrfail($stock['produit_id']) ;
            if ($produit->prix_unitaire != $stock['puht']) {
            $produit->update(['prix_unitaire' => $stock['puht'] ]);
            };

            $marcheDetails = MarcheDetail::where('marche_id',$marcheId)
                                                ->where('produit_id',$stock['produit_id'])
                                                ->get();
            if ( $marcheDetails->count() == 0) {

                    MarcheDetail::create([
                    'produit_id'=> $stock['produit_id'],
                    'marche_id'=> $marcheId,
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
        $marche = Marche::findOrfail($id);
        //dd('kdk');
        return view('dashboard.approvisionnement.marches.show',compact('marche','fournisseurs'));
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
            'no_marche' => 'required',
            'objet' => 'required',
            'ods' => 'required',
            'tva'=>'required',
            'fournisseur_id'   => 'required',
            'delais_execution'   => 'required',
            'type'  => 'required'
        ]);
        $marche = Marche::findOrfail($id);
        if ($request->tva != $marche->tva) {
            # code...
            //dd($request->tva);
            $details = MarcheDetail::where('marche_id',$id)->get();
            foreach ($details as $d) {
                # code...
                $d->update([
                    'tva' => $request->tva,
                    'prix_total' => $d->qte * $d->puht * (1 + $request->tva / 100)
                ]);
            }
        }

        $marche->update($request->all());

        Bl::where('marche_id',$marche->id)->update([
            'fournisseur_id' => $marche->fournisseur_id,
            'sous_magasin_id' => $marche->sous_magasin_id,
        ]);

        $bls =  Bl::where('marche_id',$marche->id)->get();

        foreach ($bls as $key => $b) {
            # code...
            BlDetail::where('bl_id',$b->id)->update([
                'sous_magasin_id' => $b->sous_magasin_id,
            ]);
        }

        HistoriqueMarche::create([
            'marche_id' => $marche->id,
            'user_id' =>  Auth::id()
        ]);

        Session::flash('success','Modifier avec succés');
        return redirect()->back();

    }
    public function updateStock($id,Request $request)
    {
        # code...

        $stock = MarcheDetail::findOrFail($id);
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
        $marche = Marche::findOrFail($id);
        if ($marche->bls->count() > 0) {
            return response(__('Non supprimer'));
        }else{
            MarcheDetail::where('marche_id',$id)->delete();
            $marche->delete();
            return response(__('Supprimer avec succés'));
        }

    }

    public function deleteStock($id)
    {
        //
        $marche = MarcheDetail::findOrFail($id);
        if ($marche->marche->bls->count() > 0) {
            Session::flash('error', 'Non supprimer!');
            return redirect()->back();
        }
        $marche->delete();
        //return response(__('Supprimer avec succés'));

        Session::flash('success', 'Supprimer avec succés');


        return redirect()->back();
    }

    public function rapport($id,$from=null)
    {
        # code...
         $marche = marche::findOrFail($id);

         $marcheDetails = Marche::where('id',$marche->id)->with('marcheDetails',
                                    function($q){
                                        $q->with('produit',function($q){
                                            $q->with('uniteReglementaire');
                                        });
                                    })->first();


        if ($marche->imp == false) {
            $marche->imp = true;
            $marche->update();
        }

        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'traitePar'     => 'Traité par : <strong>'.ucwords($marche->user->nom).' '.ucwords($marche->user->prenom).'</strong>',
            'duplicata' => '',
            'name'     => $marche->no_marche ,
            'date_marche'     => $marche->ods,
            'fournisseur'     => $marche->fournisseur->nom,
            'details'     => $marcheDetails,
            'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y')))
        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/marche', array('data' => $data));

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

    public function marcheUpdateAfterPrint($id)
    {
        # code...
        $marche = Marche::findOrFail($id);
        if ($marche->imp == false) {
            # code...
            $marche->imp = true;
            $marche->update();
        }

    }



    public function consulterAutreMagasin(Request $request)
    {

        if (Auth::user()->hasRole('master')) {
            # code...
            if ($request->ajax()) {
                # code...
                    $marches = Marche::when($request->no_marche, function ($q) use ($request) {
                        return $q->where('no_marche','like','%' . $request->no_marche . '%');
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
                         })->orderBy('created_at','DESC')->get();

                    return DataTables::of($marches)
                    ->addIndexColumn()
                    ->addColumn('fournisseur', function (Marche $m) {
                        return $m->fournisseur->nom;
                    })
                    ->addColumn('objet', function (Marche $m) {
                        return ( Str::length($m->objet) >= 30) ? substr($m->objet,0,30).'...' : $m->objet ;
                    })->editColumn('ods',function(Marche $m){
                        return Carbon::parse($m->ods)->format('d/m/Y');
                    })->addColumn('marche_details', function (Marche $m) {
                        $details = $m->marcheDetails;
                        return view('dashboard.approvisionnement.marches.data_table.marcheDetails', compact('details'));;
                    })
                    ->addColumn('actions', 'dashboard.approvisionnement.marches.data_table.actions')
                    ->rawColumns(['actions'])
                    ->toJson();



            }else{

                $fournisseurs = Fournisseur::all();
                $magasins = Magasin::all();

                return view('dashboard.approvisionnement.marches.autre_magasin',compact('fournisseurs','magasins'));


            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
