<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DeviseController extends Controller
{
    public function index()
    {
        //
        return view('dashboard.parametrage.devises.index');
    }

    public function data()
    {
        # code...
        $devises = Devise::select('*');

        return DataTables::of($devises)
              ->addColumn('record_select','dashboard.parametrage.devises.data_table.record_select')
              ->editColumn('created_at',function(Devise $devise){
                return $devise ->created_at->format('Y-m-d');
              })
              ->addColumn('actions','dashboard.parametrage.devises.data_table.actions')
              ->rawColumns(['record_select', 'actions'])
              ->toJson();
    }

    public function create()
    {
        //
        return view('dashboard.parametrage.devises.create');

    }


    public function store(Request $request)
    {
        //

        $request->validate([
            'designation' => 'required',
            'code' => 'required',
        ]);

        Devise::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('devises.index');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
        $devise = Devise::findOrFail($id);
        return view('dashboard.parametrage.devises.edit',compact('devise'));

    }


    public function update(Request $request, $id)
    {
        //
          $devise = Devise::findOrFail($id);
          $request->validate([
              'designation' => 'required',
              'code' => 'required',

          ]);

          $devise->update($request->all());
          session()->flash('success',__('site.updated_successfully'));

          return redirect()->route('devises.index');
    }


    public function destroy($id)
    {
        //
        $devise = Devise::findOrFail($id);

        $devise->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $devise = Devise::findOrFail($recordId);
            $this->delete($devise  );

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Devise $devise)
    {
        $devise->delete();

    }// end of delete
}
