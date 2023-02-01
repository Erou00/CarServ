<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use App\Models\Ville;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class FournisseurController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_fournisseurs')->only(['index']);
        $this->middleware('permission:create_fournisseurs')->only(['create', 'store']);
        $this->middleware('permission:update_fournisseurs')->only(['edit', 'update']);
        $this->middleware('permission:delete_fournisseurs')->only(['delete', 'bulk_delete']);

    }// en of __construct

    public function index()
    {
        //

        $villes = Ville::all();
        return view('dashboard.parametrage.fournisseurs.index',compact('villes'));


    }

    public function data()
    {
        # code...
        $fournissueur = Fournisseur::whenVilleId(request()->ville_id);


        return DataTables::of($fournissueur)
            ->addColumn('record_select', 'dashboard.parametrage.fournisseurs.data_table.record_select')
            ->addColumn('ville', function (Fournisseur $fournisseur) {
                return view('dashboard.parametrage.fournisseurs.data_table.ville', compact('fournisseur'));
            })
            ->editColumn('created_at', function (Fournisseur $fournissueur) {
                return ($fournissueur->created_at) ? $fournissueur->created_at->format('Y-m-d') : '';
            })
            ->addColumn('actions', 'dashboard.parametrage.fournisseurs.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();


    }

    public function allFournisseurs()
    {
        # code...
        $fournisseurs = Fournisseur::all();
        //dd($fournisseurs);
        return response()->json([
            "error" => false,
            "fournisseurs" => $fournisseurs,

            ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $villes = Ville::all();
        return view('dashboard.parametrage.fournisseurs.create',compact('villes'));


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
        ]);
        Fournisseur::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('fournisseurs.index');
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

        $fournisseur = Fournisseur::findOrFail($id);
        $villes = Ville::all();
        return view('dashboard.parametrage.fournisseurs.edit',compact('fournisseur','villes'));
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
        $fournissueur = Fournisseur::findOrFail($id);

        $request->validate([
            'nom' => 'required',
            'email' => [

                         Rule::unique('fournissueurs')->ignore($fournissueur->id),
                        ],

        ]);
        $fournissueur->update($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('fournisseurs.index');
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
        $fournissueur = Fournisseur::findOrFail($id);
        $fournissueur->delete();
        return response(__('Supprimé avec succès'));

    }

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $fournissueur = Fournisseur::findOrFail($recordId);
            $this->delete($fournissueur );

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Fournisseur  $fournissueur )
    {
        $fournissueur ->delete();

    }// end of delete

    public function rapport()
    {
        # code...
        $fournisseurs = Fournisseur::with('ville')->get();

        $data = [
            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $fournisseurs,
        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/fournisseurs', array('data' => $data))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }
}

