<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PaysController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_pays')->only(['index']);
        $this->middleware('permission:create_pays')->only(['create', 'store']);
        $this->middleware('permission:update_pays')->only(['edit', 'update']);
        $this->middleware('permission:delete_pays')->only(['delete', 'bulk_delete']);

    }// en of __construct
    public function index()
    {
        //
        return view('dashboard.parametrage.pays.index');
    }


    public function data()
    {
        # code...
        $pays = Pays::select('*');

        return DataTables::of($pays)
              ->addColumn('record_select','dashboard.parametrage.pays.data_table.record_select')
              ->editColumn('created_at',function(Pays $pays ){
                return $pays ->created_at->format('Y-m-d');
              })
              ->addColumn('actions','dashboard.parametrage.pays.data_table.actions')
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
        return view('dashboard.parametrage.pays.create');

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

        Pays::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('pays.index');
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
        $pays = Pays::findOrFail($id);
        return view('dashboard.parametrage.pays.edit',compact('pays'));

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
        $pays = Pays::findOrFail($id);
        $request->validate([
            'nom' => 'required',
        ]);

        $pays->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('pays.index');
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
        $pays = Pays::findOrFail($id);

        if ($pays->villes->count() > 0) {
            # code...
            session()->flash('success',__('Supprimé avec succès'));
            return response(__('Pays a des villes il peut les supprimer avant'));
        }
        $pays->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $pays= Pays::FindOrFail($recordId);
            $this->delete($pays);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Pays $pays)
    {
        $pays->delete();

    }// end of delete

}
