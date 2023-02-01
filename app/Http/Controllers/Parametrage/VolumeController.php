<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Volume;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VolumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('dashboard.parametrage.volumes.index');
    }

    public function data()
    {
        # code...
        $volume = Volume::select('*');

        return DataTables::of($volume)
              ->addColumn('record_select','dashboard.parametrage.volumes.data_table.record_select')
              ->editColumn('created_at',function(Volume $volume){
                return $volume ->created_at->format('Y-m-d');
              })
              ->addColumn('actions','dashboard.parametrage.volumes.data_table.actions')
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
        return view('dashboard.parametrage.volumes.create');

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
            'code' => 'required',
        ]);

        Volume::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('volumes.index');
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
        $volume = Volume::findOrFail($id);
        return view('dashboard.parametrage.volumes.edit',compact('volume'));

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
          $volume = Volume::findOrFail($id);
          $request->validate([
              'nom' => 'required',
              'code' => 'required',

          ]);

          $volume->update($request->all());
          session()->flash('success',__('site.updated_successfully'));

          return redirect()->route('volumes.index');
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
        $volume = Volume::findOrFail($id);

        $volume->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $volume = Volume::findOrFail($recordId);
            $this->delete($volume  );

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Volume $volume)
    {
        $volume->delete();

    }// end of delete
}
