<?php

namespace App\Http\Controllers;

use App\Models\Be;
use App\Models\Magasin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class BeController extends Controller
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
            $bls = Be::when($request->no_bl, function ($q) use ($request) {
                return $q->where('no_bl',intval(substr($request->no_bl, 0,strpos($request->no_bl,"/")+1)))
                ->where('annee','like','%' .substr($request->no_bl, strpos($request->no_bl, "/") + 1)  . '%');

            })->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date', $request->date);
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
            ->orderBy('date','DESC')->get();

           //dd($marches);
            return DataTables::of($bls)
            ->addIndexColumn()
            ->editColumn('no_sortie',function(Be $bl){
                  return $bl->no_sortie.'/'.$bl->annee;
            }) ->editColumn('date',function(Be $bl){
                return Carbon::parse($bl->date)->format('d/m/Y');
            })->addColumn('bl_details', function (Be $bl) {
                $details = $bl->bs;
                return view('dashboard.sortie_stock.beDetails', compact('details'));
            })
           // ->addColumn('be_details', 'dashboard.sortie_stock.data_table.actions')
            ->addColumn('actions', 'dashboard.sortie_stock.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

        }

        $annees = Be::select('annee')->groupBy('annee')->get();
        $magasins = Magasin::all();

        return view('dashboard.sortie_stock.index',compact('annees','magasins'));

    }

    public function fetchBonSortie(){

        $bes = Be::whereHas('bs')->with('bs',function($q){
                                $q->with('entite');
                            })->get();


        // dd($bes);
        return response()->json([
            'error' => false,
            'bes' => $bes
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       // dd($be);
        return view('dashboard.sortie_stock.mbs');
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

    public function bonSortieStore(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'designation' => 'required',
            'sous_magasin_id' => 'required',
            'bls_id'=>'required',
       ]);

       if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->messages()
            ],400);
        }else{
            $lastId = Be::where('annee',Carbon::now()->format('Y'))
                         ->where('magasin_id',Auth::user()->magasin_id)
                         ->where('sous_magasin_id',$request->sous_magasin_id)->count();

            $id = $lastId == 0 ? 1 :  $lastId + 1 ;
            $be = Be::create([
                'no_sortie'=> $id,
                'designation' => $request->designation,
                'annee' => Carbon::now()->format('Y'),
                'user_id' => Auth::id(),
                'magasin_id' => Auth::user()->magasin_id,
                'sous_magasin_id' => $request->sous_magasin_id,
                'date' => Carbon::now()
            ]);

            $be->bs()->attach($request->bls_id);

            return response()->json([
                'error' => false,
                'messages' => '',
                'id' => $be->id
            ],200);

        }


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
    }

    public function imprimer($id)
    {
        # code...
         $bl = be::findOrFail($id);

         $blDetails = Be::where('id',$bl->id)->with('bs',
                                    function($q){
                                        $q->with('entite');
                                    })->first();



                $data = [
                    'be' =>  $bl,
                    'magasin'     => '<strong>Magasin : </strong>'.$bl->magasin->nom,
                    'sous_magasin'     => '<strong>Sous Magasin : </strong>'.ucwords($bl->sousMagasin->nom),
                    'traitePar'     => '<strong>Traité par : </strong>'.ucwords($bl->user->nom).' '.ucwords($bl->user->prenom),
                    'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
                    'duplicata' => ($bl->imp) ? 'duplicata' : '',
                    'details'     => $blDetails,
                    'date'  => date('d/m/Y', strtotime(Carbon::now()->format('d/m/Y'))),
                    'currentYear' => Carbon::now()->format('Y')
                ];

                ini_set('max_execution_time', 300);
                $pdf = \PDF::loadView('dashboard/rapports/bse', array('data' => $data));

                return $pdf->stream();
    }

    public function bonSortieUpdateAfterPrint($id)
    {
        # code...
        $bl = Be::findOrFail($id);
        if ($bl->imp == false) {
            # code...
            $bl->imp = true;
            $bl->update();
        }

    }

    public function getNumCommande($sousMagasinId)
    {
        # code...
        $n = Be::where('annee',Carbon::now()->format('Y'))
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
                    $bls = Be::when($request->no_bl, function ($q) use ($request) {
                        return $q->where('no_bl',intval(substr($request->no_bl, 0,strpos($request->no_bl,"/")+1)))
                        ->where('annee','like','%' .substr($request->no_bl, strpos($request->no_bl, "/") + 1)  . '%');

                    })->when($request->date, function ($q) use ($request) {
                        return $q->whereDate('date', $request->date);
                    })->when($request->annee, function ($q) use ($request) {
                        return $q->where('annee', $request->annee);
                    })->when($request->magasin_id, function ($q) use ($request) {
                        return $q->where('magasin_id', $request->magasin_id);
                    })->when($request->sous_magasin_id, function ($q) use ($request) {
                            return $q->where('sous_magasin_id', $request->sous_magasin_id);
                    })
                    ->orderBy('date','DESC')->get();

                //dd($marches);
                    return DataTables::of($bls)
                    ->addIndexColumn()
                    ->editColumn('no_sortie',function(Be $bl){
                        return $bl->no_sortie.'/'.$bl->annee;
                    }) ->editColumn('date',function(Be $bl){
                        return Carbon::parse($bl->date)->format('d/m/Y');
                    })->addColumn('bl_details', function (Be $bl) {
                        $details = $bl->bs;
                        return view('dashboard.sortie_stock.beDetails', compact('details'));
                    })
                // ->addColumn('be_details', 'dashboard.sortie_stock.data_table.actions')
                    ->addColumn('actions', 'dashboard.sortie_stock.data_table.actions')
                    ->rawColumns(['actions'])
                    ->toJson();
            }else{

                $annees = Be::select('annee')->groupBy('annee')->get();
                $magasins = Magasin::all();

                return view('dashboard.sortie_stock.autre_magasin',compact('annees','magasins'));
            }


        }else{
            Session::flash('success', "Vous n'avez pas le droit d'accés");
            return redirect()->back();
        }


    }// end of ConsulterAutreMagasin
}
