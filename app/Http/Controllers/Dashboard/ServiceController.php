<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use  Image;


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
        $services = Service::all();

        return view('dashboard.services.index')->with('services',$services);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

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
            'image'=>['image','required'],
            'name' => ['required','unique:services'],

        ]);


        Image::make($request->image)->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('uploads/services_images/'.$request->image->hashName()));


        Service::create([
            'image' => $request->image->hashName(),
            'name'  => $request->name,
            'home_service' => ($request->home_service)  ? true : false
        ]);

        return redirect()->route('services.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //


        $request->validate([
            'image'=>'image',
            'name' => [
                'required',
                 Rule::unique('services')->ignore($service->id),
                ],

        ]);

        if($request->image){

            Storage::disk('public_uploads')->delete('services_images/'.$service->image);

            Image::make($request->image)->resize(500,500, null, function ($constraint) {
            $constraint->aspectRatio();
           })->save(public_path('uploads/services_images/'.$request->image->hashName()));

            $service->image = $request->image->hashName();
        }




        $service->name  = $request->name;
        $service->home_service = ($request->home_service)  ? true : false;

        $service->update();

        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
        Storage::disk('public_uploads')->delete('services_images/'.$service->image);

        $service->delete();
        return redirect()->route('services.index');
    }
}
