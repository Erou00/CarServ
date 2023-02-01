<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\UniteReglementaire;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UniteReglementaireController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_unite_reglementaires')->only(['index']);
        $this->middleware('permission:create_unite_reglementaires')->only(['create', 'store']);
        $this->middleware('permission:update_unite_reglementaires')->only(['edit', 'update']);
        $this->middleware('permission:delete_unite_reglementaires')->only(['delete', 'bulk_delete']);

    }// end of __construct
    public function index()
    {
        //
        return view('dashboard.parametrage.unites_reglementaire.index');
    }

    public function data()
    {
        # code...
        $unites_reglementaire = UniteReglementaire::select('*');

        return DataTables::of($unites_reglementaire)
              ->addColumn('record_select','dashboard.parametrage.unites_reglementaire.data_table.record_select')
              ->editColumn('created_at',function(UniteReglementaire $uniteReglementaire){
                return $uniteReglementaire ->created_at->format('Y-m-d');
              })
              ->addColumn('actions','dashboard.parametrage.unites_reglementaire.data_table.actions')
              ->rawColumns(['record_select', 'actions'])
              ->toJson();
    }

    public function create()
    {
        //
        return view('dashboard.parametrage.unites_reglementaire.create');

    }


    public function store(Request $request)
    {
        //

        $request->validate([
            'designation' => 'required',
            'code' => 'required',
        ]);

        UniteReglementaire::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('unite_reglementaire.index');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
        $uniteReglementaire = UniteReglementaire::findOrFail($id);
        return view('dashboard.parametrage.unites_reglementaire.edit',compact('uniteReglementaire'));

    }


    public function update(Request $request, $id)
    {
        //
          $uniteReglementaire = UniteReglementaire::findOrFail($id);
          $request->validate([
              'designation' => 'required',
              'code' => 'required',

          ]);

          $uniteReglementaire->update($request->all());
          session()->flash('success',__('site.updated_successfully'));

          return redirect()->route('unites_reglementaire.index');
    }


    public function destroy($id)
    {
        //
        $uniteReglementaire = UniteReglementaire::findOrFail($id);

        $uniteReglementaire->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $uniteReglementaire = UniteReglementaire::findOrFail($recordId);
            $this->delete($uniteReglementaire  );

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(UniteReglementaire $uniteReglementaire)
    {
        $uniteReglementaire->delete();

    }// end of delete
}
