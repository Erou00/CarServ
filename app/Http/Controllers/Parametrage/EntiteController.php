<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Bs;
use App\Models\Demande;
use App\Models\Entite;
use App\Models\TypeEntite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EntiteController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_entites')->only(['index']);
        $this->middleware('permission:create_entites')->only(['create', 'store']);
        $this->middleware('permission:update_entites')->only(['edit', 'update']);
        $this->middleware('permission:delete_entites')->only(['delete', 'bulk_delete']);

    }// en of __construct

    public function index()
    {
        //
        $entites = Entite::all();
        $types = TypeEntite::all();

        return view('dashboard.parametrage.entites.index',compact('entites','types'));
    }

    public function data()
    {
        $entites = Entite::when(request()->entite_id,function($q){
                                    $q->where('entite_mere_id',request()->entite_id);
                            })
                          ->when(request()->type_entite_id,function($q){
                                $q->where('type_entite_id',request()->type_entite_id);
                          })->get();

        return DataTables::of($entites)
            ->addColumn('record_select', 'dashboard.parametrage.entites.data_table.record_select')
            ->addColumn('entite_mere', function (Entite $entite) {
                return view('dashboard.parametrage.entites.data_table.entite_mere', compact('entite'));
            })
            ->addColumn('type_entite', function (Entite $e) {
                    return $e->typeEntite ? $e->typeEntite->type : '';
            })
            ->editColumn('created_at', function (Entite $entite) {
                return ($entite->created_at) ? $entite->created_at->format('Y-m-d') : '';
            })
            ->addColumn('actions', 'dashboard.parametrage.entites.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function allEntites()
    {
        # code...
        $entites = Entite::all();
        return response()->json([
            "error" => false,
            "entites" => $entites,

            ],200);

    }
    public function create()
    {
        //
        $entites = Entite::all();
        $types = TypeEntite::all();
        return view('dashboard.parametrage.entites.create',compact('entites','types'));
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
            'nom' => 'required',
            // 'entite_mere_id' => 'required'
        ]);

        Entite::create($request->all());
        session()->flash('success','Ajouter avec succés');

        return redirect()->route('entites.index');
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
    public function edit( $id)
    {
        //
        $entite = Entite::findOrFail($id);
        $mereEntites = Entite::all();
        $types = TypeEntite::all();

        return view('dashboard.parametrage.entites.edit',compact('entite','mereEntites','types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $entite = Entite::findOrFail($id);
        $request->validate([

            'nom' => 'required',
            // 'entite_mere_id' => 'required'
        ]);

        $entite->update($request->all());
        session()->flash('success','Modifier avec succés');

        return redirect()->route('entites.index');
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
        $entite = Entite::findOrFail($id);
        $bs =Bs::where('entite_id',$entite->id)->count();
        $dd =Demande::where('entite_id',$entite->id)->count();

        if ( $bs > 0 ||  $dd  > 0) {
            # code...
            return response(__(' Non Supprimé'));
        }else{
        $entite->delete();
        session()->flash('success',__('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

        }

    }


    public function bulkDelete()
    {

        foreach (json_decode(request()->record_ids) as $recordId) {
            $this->delete($recordId);
        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete($id)
    {
        $entite = Entite::findOrFail($id);
        $entite->delete();

    }// end of delete

    public function rapport()
    {
        # code...
        $entites = Entite::with('entiteMere')->get();

        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $entites,

        ];


        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/entites', array('data' => $data))->setPaper('a4', 'landscape');

        return $pdf->download();
    }
}
