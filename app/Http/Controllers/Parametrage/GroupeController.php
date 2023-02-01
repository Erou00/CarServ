<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GroupeController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_groupes')->only(['index']);
        $this->middleware('permission:create_groupes')->only(['create', 'store']);
        $this->middleware('permission:update_groupes')->only(['edit', 'update']);
        $this->middleware('permission:delete_groupes')->only(['delete', 'bulk_delete']);

    }// en of __construct


    public function index()
    {
        //

        return view('dashboard.parametrage.groupes.index');
    }

    public function data()
    {
        $groupes = Groupe::select('*');

        return DataTables::of($groupes)
            ->addColumn('record_select', 'dashboard.parametrage.groupes.data_table.record_select')
            ->editColumn('created_at', function (Groupe $Groupe) {
                return $Groupe->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.parametrage.groupes.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('dashboard.parametrage.groupes.create');
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

        Groupe::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('groupes.index');
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
    public function edit( Groupe $groupe)
    {
        //
        //dd($groupe);
        return view('dashboard.parametrage.groupes.edit',compact('groupe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Groupe $groupe)
    {
        //
        $request->validate([
            'nom' => 'required',
        ]);

        $groupe->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('groupes.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Groupe $groupe)
    {
        //
        $groupe->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $Groupe = Groupe::FindOrFail($recordId);
            $this->delete($Groupe);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Groupe $groupe)
    {
        $groupe->delete();

    }// end of delete
}
