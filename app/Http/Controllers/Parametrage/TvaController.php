<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Tva;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TvaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('dashboard.parametrage.tva.index');
    }

    public function data()
    {
        $tvas = Tva::select('*');

        return DataTables::of($tvas)
            ->addColumn('record_select', 'dashboard.parametrage.tva.data_table.record_select')
            ->editColumn('created_at', function (Tva $tva) {
                return $tva->created_at->format('d/m/Y');
            })
            ->addColumn('actions', 'dashboard.parametrage.tva.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function alltvas()
    {
        # code...
        $tvas = Tva::all();
        return response()->json([
            "error" => false,
            "tvas" => $tvas ,

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
        return view('dashboard.parametrage.tva.create');
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
            'tva' => 'required',
        ]);

        Tva::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('tvas.index');
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
    public function edit( Tva $tva)
    {
        //
        //dd($tva);
        return view('dashboard.parametrage.tva.edit',compact('tva'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tva $tva)
    {
        //
        $request->validate([
            'tva' => 'required',
        ]);

        $tva->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('tvas.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tva $tva)
    {
        //
        $tva->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $tva = Tva::FindOrFail($recordId);
            $this->delete($tva);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Tva $tva)
    {
        $tva->delete();

    }// end of delete

}
