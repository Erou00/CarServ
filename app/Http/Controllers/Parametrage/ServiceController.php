<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('dashboard.parametrage.services.index');
    }

    public function data()
    {
        $services = Service::select('*');

        return DataTables::of($services)
            ->addColumn('record_select', 'dashboard.parametrage.services.data_table.record_select')
            ->editColumn('created_at', function (Service $service) {
                return $service->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.parametrage.services.data_table.actions')
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
        return view('dashboard.parametrage.services.create');
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
        $request->all();
        $request->validate([
            'nom' => 'required',
        ]);

        Service::create($request->all());
        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('services.index');
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
    public function edit( Service $service)
    {
        //
        //dd($service);
        return view('dashboard.parametrage.services.edit',compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
        $request->validate([
            'nom' => 'required',
        ]);

        $service->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('services.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
        $service->delete();
        session()->flash('success',__('Supprimé avec succès'));

        return response(__('Supprimé avec succès'));
    }


    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $service = Service::FindOrFail($recordId);
            $this->delete($service);

        }//end of for each

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));

    }// end of bulkDelete

    private function delete(Service $service)
    {
        $service->delete();

    }// end of delete

}
