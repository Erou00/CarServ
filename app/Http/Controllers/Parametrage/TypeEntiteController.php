<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\TypeEntite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TypeEntiteController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_type_entites')->only(['index']);
        $this->middleware('permission:create_type_entites')->only(['create', 'store']);
        $this->middleware('permission:update_type_entites')->only(['edit', 'update']);
        $this->middleware('permission:delete_type_entites')->only(['delete', 'bulk_delete']);

    }// en of __construct

    public function index()
    {
        //

        return view('dashboard.parametrage.type_entites.index');
    }

    public function data()
    {
        $types = TypeEntite::select('*');

        //dd($types);
        return DataTables::of($types)
            ->addColumn('record_select', 'dashboard.parametrage.type_entites.data_table.record_select')
            ->editColumn('created_at', function (TypeEntite $TypeEntite) {
                return $TypeEntite->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.parametrage.type_entites.data_table.actions')
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
        return view('dashboard.parametrage.type_entites.create');
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
            'type' => 'required',
        ]);

        TypeEntite::create($request->all());
        session()->flash('success',__('Ajouter avec succès'));

        return redirect()->route('types.index');
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
    public function edit( TypeEntite $type)
    {
        //
        //dd($type);
        return view('dashboard.parametrage.type_entites.edit',compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeEntite $type)
    {
        //
        $request->validate([
            'type' => 'required',
        ]);

        $type->update($request->all());
        session()->flash('success',__('Modifier avec succès'));

        return redirect()->route('types.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeEntite $type)
    {
        //

        $type->delete();

        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $TypeEntite = TypeEntite::FindOrFail($recordId);
            $this->delete($TypeEntite);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(TypeEntite $type)
    {
        $type->delete();

    }// end of delete

    public function rapport()
    {
        # code...
        $types= TypeEntite::all();

        $data = [

            'invoice-date' => Carbon::now()->format('d/m/Y  H:i'),
            'details'     => $types,

        ];

        ini_set('max_execution_time', 300);
        $pdf = \PDF::loadView('dashboard/rapports/type_entites', array('data' => $data))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

}


