<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Volume;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $volumes = Volume::all();
        return view('dashboard.parametrage.classes.index',compact('volumes'));
    }


    public function data()
    {
        # code...
        $classes = Classe::whenVolumeId(request()->volume_id);

        //dd($classes);
        return DataTables::of($classes)
              ->addColumn('record_select','dashboard.parametrage.classes.data_table.record_select')
              ->addColumn('volume', function (Classe $classe) {
                return view('dashboard.parametrage.classes.data_table.volume', compact('classe'));
            })
              ->editColumn('created_at',function(Classe $classe){
                return $classe->created_at->format('Y-m-d');
              })
              ->addColumn('actions','dashboard.parametrage.classes.data_table.actions')
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
        $volumes = Volume::all();
        return view('dashboard.parametrage.classes.create')->with('volumes',$volumes);
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
            'volume_id' => 'required',
            'code' => 'required'
        ]);

        Classe::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('classes.index');
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
        $volumes = Volume::all();
        $classe = Classe::findOrFail($id);
        return view('dashboard.parametrage.classes.edit',[
            'classe' => $classe,
            'volumes' =>$volumes
        ]);
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

        $classe = Classe::findOrFail($id);
        $request->validate([
            'nom' => 'required',
            'volume_id' => 'required'
        ]);

        $classe->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('classes.index');

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
        $classe = Classe::findOrFail($id);
        $classe->delete();

        return response(__('Supprimé avec succès'));

    }

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $classe = Classe::FindOrFail($recordId);
            $this->delete($classe);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Classe $classe )
    {
        $classe->delete();

    }// end of delete
}
