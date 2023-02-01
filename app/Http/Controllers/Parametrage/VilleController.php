<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use App\Models\Ville;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VilleController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_villes')->only(['index']);
        $this->middleware('permission:create_villes')->only(['create', 'store']);
        $this->middleware('permission:update_villes')->only(['edit', 'update']);
        $this->middleware('permission:delete_villes')->only(['delete', 'bulk_delete']);

    }// end of __construct
    public function index()
    {
        //
        $pays = Pays::all();
        return view('dashboard.parametrage.villes.index',compact('pays'));
    }


    public function data()
    {
        # code...
        $villes = Ville::whenPaysId(request()->pays_id);

        //dd(request()->pays);
        return DataTables::of($villes)
              ->addColumn('record_select','dashboard.parametrage.villes.data_table.record_select')
              ->addColumn('pays', function (Ville $ville) {
                return view('dashboard.parametrage.villes.data_table.pays', compact('ville'));
            })
              ->editColumn('created_at',function(Ville $ville){
                return $ville->created_at->format('Y-m-d');
              })
              ->addColumn('actions','dashboard.parametrage.villes.data_table.actions')
              ->rawColumns(['record_select', 'actions'])
              ->toJson();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $pays = Pays::all();
        return view('dashboard.parametrage.villes.create')->with('pays',$pays);

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
            'pays_id' => 'required'
        ]);

        Ville::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('villes.index');
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
    public function edit(Ville $ville)
    {
        //
        $pays = Pays::all();
        return view('dashboard.parametrage.villes.edit',[
            'ville' => $ville,
            'pays' => $pays
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ville $ville)
    {
        //

        $request->validate([
            'nom' => 'required',
            'pays_id' => 'required'
        ]);

        $ville->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('villes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ville $ville)
    {
        //
        $ville->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $ville= Ville::FindOrFail($recordId);
            $this->delete($ville);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Ville $ville)
    {
        $ville->delete();

    }// end of delete
}
