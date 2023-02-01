<?php

namespace App\Http\Controllers;

use App\Models\Inventaire;
use App\Models\InventaireDetail;
use App\Models\Magasin;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InventaireController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_inventaires')->only(['index']);
        $this->middleware('permission:create_inventaires')->only(['create', 'store','inventaireWithDetails']);
        $this->middleware('permission:update_inventaires')->only(['edit', 'update']);
        $this->middleware('permission:delete_inventaires')->only(['delete', 'bulk_delete']);

    }// end of __construct

    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            # code...
            $inventaires = inventaire::when($request->no_inventaire, function ($q) use ($request) {
                return $q->where('no_inventaire','like','%' . $request->no_inventaire . '%');
            })->when($request->objet, function ($q) use ($request) {
                return $q->where('objet','like','%' . $request->objet . '%');
            })->when($request->etat, function ($q) use ($request) {
                return $q->where('etat', $request->etat);
            })->where('magasin_id',Auth::user()->magasin_id)
            ->get();

           //dd($marches);
            return DataTables::of($inventaires)
            ->addIndexColumn()
            ->addColumn('inventaire_details', function (Inventaire $c) {
                $details = $c->inventaireDetails;
                return view('dashboard.inventaires.data_table.inventaireDetails', compact('details'));;
            })
            ->addColumn('actions', 'dashboard.inventaires.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();
        }


        return view('dashboard.inventaires.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $n = Inventaire::where('magasin_id',Auth::user()->magasin_id)->count();
        $inv = ($n == 0 ) ? 1 :  $n+1 ;

        return view('dashboard.inventaires.create',compact('inv'));
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

    public function inventaireWithDetails(Request $request)
    {
        # code...

        // dd($request->all());
        $request->validate([
            'no_inventaire' => 'required',
            'etat' => 'required',
            'date_preparation' => 'required',
            'inventaireDetails.*.produit_id' => 'required',

        ]);


            # code...
            $inventaireData = $request->except('inventaireDetails');
            $inventaireData['user_id'] = Auth::id();
            $inventaireData['magasin_id'] = Auth::user()->magasin_id;
            $inventaire = Inventaire::create($inventaireData);

              if ($request->inventaireDetails) {
                # code...
                foreach ($request->inventaireDetails as $inv) {
                   InventaireDetail::create([
                    'produit_id'=> $inv['produit_id'],
                    'inventaire_id'=> $inventaire->id,
                    'magasin_id'=> $inventaire->magasin_id,
                    'user_id'=> Auth::id()
                   ]);
                    # code...
                }
              }

              if ($request->imprimer) {
                # code...
                return $this->rapport($inventaire->id,'fun');
              }

              Session::flash('success', 'Ajouter avec succés');
              return redirect()->back();

    }

    public function addToInventaireDetails(Request $request , $id)
    {
        # code...
        foreach ($request->inventaireDetails as $inv) {
            InventaireDetail::create([
                'produit_id'=> $inv['produit_id'],
                'inventaire_id'=> $id,
                'qte'=> $inv['qte'],
                'qte_stock'=> $inv['qte_stock'],
                'date_premption'=> $inv['date_premption'],
                'lot'=> $inv['lot'],
                'magasin_id'=>$inv['magasin_id'],
                'user_id'=> Auth::id()
            ]);
             # code...
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
        $inventaire = Inventaire::findOrfail($id);
        $magasins = Magasin::all();
        return view('dashboard.inventaires.show',compact('inventaire','magasins'));
    }

    public function inventaireById($id)
    {
        //
        $inventaire = Inventaire::where('id',$id)->with('inventaireDetails',function($q){
                                                        $q->with('produit');
                                                        $q->with('magasin');
                                                    })->first();
        return response()->json([
            'error' => false,
            'inventaire' => $inventaire,
            'details' => $inventaire->inventaireDetails

        ],200);
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
       //dd( $request->all());
       $inventaire = Inventaire::findOrfail($id);
       if ($request->etat == 'verification') {
        # code...
            if (!Auth::user()->isAbleTo('verification_inventaires')) {
                session()->flash('error', "vous n'avez pas la permission");
                return redirect()->back();
            }
       }

       if ($request->etat == 'validation') {
        # code...
            if (!Auth::user()->isAbleTo('validation_inventaires')) {
                session()->flash('error', "vous n'avez pas la permission");
                return redirect()->back();
            }
       }

       if ($inventaire->etat == 'validation') {
        # code...
            session()->flash('error', 'inventaire déjà validée');
            return redirect()->back();
       }

        $request->validate([
            'etat' => 'required',
            'date_preparation' => 'required',
            'inventaireDetails.*.produit_id' => 'required',
            'inventaireDetails.*.qte_inventorie' => 'required|numeric|min:0',

        ]);

        //dd($request->inventaireDetails);
        foreach ($request->inventaireDetails as $inv) {
            # code...
          $invDetails =   InventaireDetail::where('inventaire_id',$inventaire->id)
                            ->where('produit_id',$inv['produit_id'])->update([
                                'qte_inventorie' => $inv['qte_inventorie'],
                                'magasin_id' => Auth::user()->magasin_id,
                            ]);


                if ($request->etat == 'validation') {
                    # code...
                    $stock = Stock::where('produit_id',$inv['produit_id'])
                                    ->where('magasin_id',Auth::user()->magasin_id)->first() ;
                    if ($stock) {
                        $stock->update([
                            'old_qte' => ($stock->qte ) ? $stock->qte : 0,
                            'qte' =>  $inv['qte_inventorie']

                        ]);
                    }else{
                        Stock::create([
                            'produit_id'=> $inv['produit_id'],
                            'old_qte' =>  0,
                            'qte'=> $inv['qte_inventorie'],
                            'magasin_id'=> Auth::user()->magasin_id,
                            'user_id'=> Auth::id()
                        ]);
                    }
                }

        }


        $inventaire->update($request->except('inventaireDetails'));

        session()->flash('success',__('Modifier avec succès'));
        return redirect()->back();

    }

    public function inventaireDetailsUpdate(Request $request, $id)
    {
        # code...
        $inventaireDetails = InventaireDetail::findOrfail($id);
        $inventaireDetails->update($request->all());

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
        $inventaire = Inventaire::findOrfail($id);
        $inventaire->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return redirect()->back();
    }

    public function deleteInventaireDetails($id)
    {
        //
        $ind = InventaireDetail::findOrFail($id);
        $ind->delete();
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

         $inventaire = Inventaire::findOrFail($id);

         $inventaireDetails = Inventaire::where('id',$inventaire->id)->with('inventaireDetails',
                                    function($q){
                                        $q->with('produit',function($q){
                                            $q->with('uniteReglementaire');
                                        })->with('magasin');
                                    })->first();


        // dd($bl);

        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'duplicata' => '',
            'date'   => $inventaire->etat == 'preparation' ? Carbon::parse($inventaire->date_preparation)->format('d/m/Y') : ($inventaire->etat == 'verifification' ? Carbon::parse($inventaire->date_verification)->format('d/m/Y') : Carbon::parse($inventaire->date_validation)->format('d/m/Y')),
            'no_inventaire'     => $inventaire->no_inventaire,
            'etat'     => $inventaire->etat,
            'details'     => $inventaireDetails,
        ];

        //Carbon::parse($inventaire->date)->format('d/m/Y')
        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/inventaire', array('data' => $data));

        if ($from) {
                # code...
                $pdf->save('documents/document.pdf');
                Session::flash('download.in.the.next.request','documents/document.pdf');
                return redirect()->back();

        }

        return $pdf->stream();
    }



public function consulterAutreMagasin(Request $request)
    {

        if (Auth::user()->hasRole('master')) {
            # code...
            if ($request->ajax()) {

                if ($request->ajax()) {
                    # code...
                    $inventaires = inventaire::when($request->no_inventaire, function ($q) use ($request) {
                            return $q->where('no_inventaire','like','%' . $request->no_inventaire . '%');
                        })->when($request->objet, function ($q) use ($request) {
                            return $q->where('objet','like','%' . $request->objet . '%');
                        })->when($request->etat, function ($q) use ($request) {
                            return $q->where('etat', $request->etat);
                        })->when($request->magasin_id, function ($q) use ($request) {
                                return $q->where('magasin_id', $request->magasin_id);
                        })
                    ->get();

                //dd($marches);
                    return DataTables::of($inventaires)
                    ->addIndexColumn()
                    ->addColumn('inventaire_details', function (Inventaire $c) {
                        $details = $c->inventaireDetails;
                        return view('dashboard.inventaires.data_table.inventaireDetails', compact('details'));;
                    })
                    ->addColumn('actions', 'dashboard.inventaires.data_table.actions')
                    ->rawColumns(['actions'])
                    ->toJson();
                }


            }else{

                $magasins = Magasin::all();

                return view('dashboard.inventaires.autre_magasin',compact('magasins'));

            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin

}



