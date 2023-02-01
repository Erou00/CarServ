<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Magasin;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MagasinController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_magasins')->only(['index']);
        $this->middleware('permission:create_magasins')->only(['create', 'store']);
        $this->middleware('permission:update_magasins')->only(['edit', 'update']);
        $this->middleware('permission:delete_magasins')->only(['delete', 'bulk_delete']);

    }// en of __construct

    public function index()
    {
        //

        return view('dashboard.parametrage.magasins.index');
    }

    public function data()
    {
        $magasins = Magasin::select('*');

        return DataTables::of($magasins)
            ->addColumn('record_select', 'dashboard.parametrage.magasins.data_table.record_select')
            ->editColumn('created_at', function (Magasin $magasin) {
                return $magasin->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.parametrage.magasins.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function allMagasins()
    {
        # code...
        $magasins = Magasin::all();
        return response()->json([
            "error" => false,
            "magasins" => $magasins ,

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
        return view('dashboard.parametrage.magasins.create');
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

        Magasin::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('magasins.index');
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
    public function edit( Magasin $magasin)
    {
        //
        //dd($magasin);
        return view('dashboard.parametrage.magasins.edit',compact('magasin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Magasin $magasin)
    {
        //
        $request->validate([
            'nom' => 'required',
        ]);

        $magasin->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('magasins.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Magasin $magasin)
    {
        //
        $magasin->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $magasin = Magasin::FindOrFail($recordId);
            $this->delete($magasin);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Magasin $magasin)
    {
        $magasin->delete();

    }// end of delete

}
