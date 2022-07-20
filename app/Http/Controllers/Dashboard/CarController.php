<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cars = Car::all();
        return view('dashboard.cars.index',['cars'=> $cars]);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $car = Car::find($id);


        return view('dashboard.cars.car-details',['car'=>$car]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $vehicule = Car::find($id);



        Storage::disk('public_uploads')->delete('carte_grise/'.$vehicule->carte_grise_back);
        Storage::disk('public_uploads')->delete('carte_grise/'.$vehicule->carte_grise_front);

        if ($vehicule->images) {
            # code...
            foreach ( json_decode($vehicule->images) as $key=>$image) {
            Storage::disk('public_uploads')->delete('cars_images/'.$image[$key]);
            }
        }


        $demande = Demande::where('car_id',$vehicule->id);
        $demande->delete();
        $vehicule->delete();

        toastr()->success('Car deleted Successfully');
        return redirect()->back();
    }

    public function carForSale()
    {
        # code...
        $cars = Car::where('for_sale',true)->get();

        return view('dashboard.cars.car-for-sale',['cars'=> $cars]);
    }


    public function carValidate($id,Request $request)
    {
        # code...
        $car = Car::find($id);

        if ($request->validate == 'validate') {
            # code...
            $car->validate = true;
        }
        elseif ($request->etat == 'non-validate') {
            # code...
            $car->validate = false;
        }

        $car->update();

        return redirect()->route('dashboard.carForSale');
    }
}
