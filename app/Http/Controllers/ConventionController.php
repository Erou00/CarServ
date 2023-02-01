<?php

namespace App\Http\Controllers;

use App\Models\Bl;
use App\Models\BlDetail;
use App\Models\Convention;
use App\Models\ConventionDetail;
use App\Models\Fournisseur;
use App\Models\HistoriqueConvention;
use App\Models\Magasin;
use App\Models\Produit;
use App\Models\Tva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class ConventionController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_conventions')->only(['index']);
        $this->middleware('permission:create_conventions')->only(['create', 'store','storeWithStock']);
        $this->middleware('permission:update_conventions')->only(['edit', 'update']);
        $this->middleware('permission:delete_conventions')->only(['delete', 'bulk_delete']);

    }// end of __construct
    public function index()
    {
        //
        $fournisseurs = Fournisseur::all();
        $magasins = Magasin::all();

        return view('dashboard.approvisionnement.conventions.index',compact('fournisseurs','magasins'));
    }

    public function allConventions()
    {
        # code...

        $userSousMagasin =  Auth::user()->sousmagasins->map(function ($item){
            return $item->id;
        })->toArray();

        $conventions = DB::table('conventions')
        ->selectRaw("(SELECT SUM(convention_details.qte) FROM convention_details
        WHERE  convention_details.convention_id = conventions.id
        GROUP BY convention_details.convention_id) as qte_demandee,
        (SELECT SUM(bl_details.qte_livree) FROM bl_details,bls
        WHERE bl_details.bl_id = bls.id
        AND bls.convention_id = conventions.id
        GROUP BY bls.convention_id) as qte_livree,
        conventions.*")
        ->where('magasin_id', Auth::user()->magasin_id)
        ->whereIn('sous_magasin_id',$userSousMagasin)->get();


        $collection =  $conventions->filter(function ($item, $key){
            return $item->qte_demandee > $item->qte_livree ;
        });


        return response()->json([
            'error' =>false,
            'conventions' => $collection
        ]);
    }


    public function data(Request $request)
    {
        $conventions = Convention::when($request->no_convention, function ($q) use ($request) {
            return $q->where('no_convention','like','%' . $request->no_convention . '%');
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

       //dd($conventions);
        return DataTables::of($conventions)
        ->addIndexColumn()
        ->addColumn('magasin', function (Convention $c) {

            return $c->sousMagasin() ? $c->sousMagasin->nom:'';
        })
        ->addColumn('fournisseur', function (Convention $c) {
            return $c->fournisseur->nom;
        })
        ->addColumn('objet', function (Convention $c) {
            return ( Str::length($c->objet) >= 30) ? substr($c->objet,0,30).'...' : $c->objet ;
        })
        ->editColumn('ods', function (Convention $c) {
            return Carbon::parse($c->ods)->format('d/m/Y') ;
        })
        ->addColumn('convention_details', function (Convention $c) {
            $details = $c->conventionDetails;
            return view('dashboard.approvisionnement.conventions.data_table.conventionDetails', compact('details'));;
        })
        ->addColumn('montant', function (Convention $c) {
            $montants = 0;
                foreach ($c->conventionDetails as $item) {
                    $montants += $item->prix_total;
                }
            return number_format($montants,2).' DH';
        })
        ->addColumn('actions', 'dashboard.approvisionnement.conventions.data_table.actions')
        ->rawColumns(['actions'])
        ->toJson();

    }// end of data

    public function create()
    {
        //

        $tva = Tva::all();
       //dd($conventions);
        return view('dashboard.approvisionnement.conventions.create',compact('tva'));
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
            'no_convention' => 'required',
            'objet' => 'required',
            'ods' => 'required',
            'fournisseur_id'   => 'required',
            'delais_execution'   => 'required',
            'sous_magasin_id'   => 'required',


        ]);

            # code...
            $conventionData = $request->except('ConventionDetails');
            $conventionData['user_id'] = Auth::id();
            $conventionData['magasin_id'] = Auth::user()->magasin_id;
            $convention = Convention::create($conventionData);


              if ($request->conventionDetails) {
                # code...
                foreach ($request->conventionDetails as $stock) {

                    $produit = Produit::findOrfail($stock['produit_id']) ;
                    if ($produit->prix_unitaire != $stock['puht']) {
                    $produit->update(['prix_unitaire' => $stock['puht'] ]);
                    };

                   ConventionDetail::create([
                    'produit_id'=> $stock['produit_id'],
                    'convention_id'=> $convention->id,
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
                return $this->rapport($convention->id,'fun');
              }

              Session::flash('success', 'Ajouter avec succés');
              return redirect()->back();


    //
     }

    public function addToStock($conventionId,Request $request)
    {
        # code...
        foreach ($request->conventionDetails as $stock) {
            $produit = Produit::findOrfail($stock['produit_id']) ;
            if ($produit->prix_unitaire != $stock['puht']) {
            $produit->update(['prix_unitaire' => $stock['puht'] ]);
            };

            $ConventionDetails = ConventionDetail::where('convention_id',$conventionId)
                                                ->where('produit_id',$stock['produit_id'])
                                                ->get();
            if ( $ConventionDetails->count() == 0) {

                    ConventionDetail::create([
                    'produit_id'=> $stock['produit_id'],
                    'convention_id'=> $conventionId,
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
        $convention = Convention::findOrfail($id);
        //dd('kdk');
        return view('dashboard.approvisionnement.conventions.show',compact('convention','fournisseurs'));
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
            'no_convention' => 'required',
            'objet' => 'required',
            'ods' => 'required',
            'tva'=>'required',
            'fournisseur_id'   => 'required',
            'delais_execution'   => 'required',
        ]);
        $convention = Convention::findOrfail($id);
        if ($request->tva != $convention->tva) {
            # code...
            //dd($request->tva);
            $details = ConventionDetail::where('convention_id',$id)->get();
            foreach ($details as $d) {
                # code...
                $d->update([
                    'tva' => $request->tva,
                    'prix_total' => $d->qte * $d->puht * (1 + $request->tva / 100)
                ]);
            }
        }


        $convention->update($request->all());

        Bl::where('convention_id',$convention->id)->update([
            'fournisseur_id' => $convention->fournisseur_id,
            'sous_magasin_id' => $convention->sous_magasin_id,
        ]);

        $bls =  Bl::where('convention_id',$convention->id)->get();

        foreach ($bls as $key => $b) {
            # code...
            BlDetail::where('bl_id',$b->id)->update([
                'sous_magasin_id' => $b->sous_magasin_id,
            ]);
        }


        HistoriqueConvention::create([
            'convention_id' => $convention->id,
            'user_id' =>  Auth::id()
        ]);

        Session::flash('success','Modifier avec succés');
        return redirect()->back();

    }
    public function updateStock($id,Request $request)
    {
        # code...

        $stock = ConventionDetail::findOrFail($id);
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
        $convention = Convention::findOrFail($id);
        if ($convention->bls->count() > 0) {
            return response(__('Non supprimer'));
        }else{
            ConventionDetail::where('convention_id',$id)->delete();
            $convention->delete();
            return response(__('Supprimer avec succés'));
        }

    }

    public function deleteStock($id)
    {
        //
        $convention = ConventionDetail::findOrFail($id);
        if ($convention->convention->bls->count() > 0) {
            Session::flash('error', 'Non supprimer!');
            return redirect()->back();
        }
        $convention->delete();
        //return response(__('Supprimer avec succés'));

        Session::flash('success', 'Supprimer avec succés');


        return redirect()->back();
    }

    public function rapport($id,$from=null)
    {
        # code...
         $convention = Convention::findOrFail($id);

         $conventionDetails = Convention::where('id',$convention->id)->with('conventionDetails',
                                    function($q){
                                        $q->with('produit',function($q){
                                            $q->with('uniteReglementaire');
                                        });
                                    })->first();


        if ($convention->imp == false) {
            $convention->imp = true;
            $convention->update();
        }

        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'traitePar'     => 'Traité par : <strong>'.ucwords($convention->user->nom).' '.ucwords($convention->user->prenom).'</strong>',
            'duplicata' => '',
            'name'     => $convention->no_convention ,
            'date_convention'     => $convention->ods,
            'fournisseur'     => $convention->fournisseur->nom,
            'details'     => $conventionDetails,
            'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y')))
        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/convention', array('data' => $data));

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

    public function conventionUpdateAfterPrint($id)
    {
        # code...
        $convention = Convention::findOrFail($id);
        if ($convention->imp == false) {
            # code...
            $convention->imp = true;
            $convention->update();
        }

    }


    public function consulterAutreMagasin(Request $request)
    {

        if (Auth::user()->hasRole('master')) {
            # code...
            if ($request->ajax()) {
                # code...
                    $conventions = Convention::when($request->no_convention, function ($q) use ($request) {
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
                         })->orderBy('created_at','DESC')->get();

                    return DataTables::of($conventions)
                    ->addIndexColumn()
                    ->addColumn('fournisseur', function (Convention $c) {
                        return $c->fournisseur->nom;
                    })
                    ->addColumn('objet', function (Convention $c) {
                        return ( Str::length($c->objet) >= 30) ? substr($c->objet,0,30).'...' : $c->objet ;
                    })->editColumn('ods',function(Convention $m){
                        return Carbon::parse($m->ods)->format('d/m/Y');
                    })->addColumn('convention_details', function (Convention $c) {
                        $details = $c->conventionDetails;
                        return view('dashboard.approvisionnement.conventions.data_table.conventionDetails', compact('details'));
                    })->addColumn('montant', function (Convention $c) {
                        $montants = 0;
                            foreach ($c->conventionDetails as $item) {
                                $montants += $item->prix_total;
                            }
                        return number_format($montants,2).' DH';
                    })
                    ->addColumn('actions', 'dashboard.approvisionnement.conventions.data_table.actions')
                    ->rawColumns(['actions'])
                    ->toJson();



            }else{

                $fournisseurs = Fournisseur::all();
                $magasins = Magasin::all();

                return view('dashboard.approvisionnement.conventions.autre_magasin',compact('fournisseurs','magasins'));


            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
