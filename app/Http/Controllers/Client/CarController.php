<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Carbirant;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use  Image;

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
        $cars = Car::where('user_id',Auth::user()->id)->get();
        return view('client.cars.index',['cars'=> $cars]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $carbirants = DB::table('carbirants')
        ->get();
        $marques = DB::table('marks')
        ->get();

        $origins = DB::table('origins')
        ->get();

        $kilometers = DB::table('kilometers')
        ->get();

         return view('client.cars.create')
         ->with('marques', $marques)
         ->with('carbirants',$carbirants)
         ->with('kilometers',$kilometers)
         ->with('origins',$origins);
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


        $this->validate($request,[
            'marque_id' => 'required',
            'model_id' => 'required',
            'year' => 'required',
            'carbirant_id' => 'required',
            'carte_grise_front' => 'required',
            'carte_grise_back' => 'required',
        ]);




        Image::make($request->carte_grise_front)->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('uploads/carte_grise/'.$request->carte_grise_front->hashName()));

        Image::make($request->carte_grise_back)->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('uploads/carte_grise/'.$request->carte_grise_back->hashName()));


        $car = new Car;


        //dd($request->all());

        $car->marque_id=$request->marque_id;
        $car->model_id=$request->model_id;
        $car->year=$request->year;
        $car->carbirant_id=$request->carbirant_id;
        $car->carte_grise_front=$request->carte_grise_front->hashName();
        $car->carte_grise_back=$request->carte_grise_back->hashName();
        $car->user_id = Auth::user()->id;

        if ($request->for_sale) {
            # code...
            $this->validate($request,[
                'title' => 'required',
                'fiscal_power' => 'required',
                'kilo' => 'required',
                'doors' => 'required',
                'origin_id' => 'required',
                'gear_box' => 'required',
                'first_hand' => 'required',
                'images' => 'required',
                'images.*' => 'mimes:jpeg,jpg,png',
                'description' => 'required',
                'price' => 'required'
            ]);

            $request_data = [];
            if($request->hasfile('images')) {

                 foreach ($request->images as $image) {
                    # code...
                    Image::make($image)->resize(1020, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(public_path('uploads/cars_images/'.$image->hashName()));

                    array_push($request_data,$image->hashName());
                 }

            }

            $car->title=$request->title;
            $car->slug=Str::of($request->title)->slug('-');
            $car->for_sale=true;
            $car->fiscal_power=$request->fiscal_power;
            $car->kilometre_id=$request->kilo;
            $car->doors=$request->doors;
            $car->origin_id=$request->origin_id;
            $car->gearbox=$request->gear_box;
            $car->images =  json_encode($request_data) ;
            $car->description = $request->description;
            $car->price = $request->price;
            $car->first_hand=($request->first_hand == "yes") ? true:false;
        }

            $car->save();

        return redirect()->route('cars.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        //
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
        $vehicule = Car::find($car->id);
        $carbirants = DB::table('carbirants')
        ->get();
        $marques = DB::table('marks')
        ->get();

        $origins = DB::table('origins')
        ->get();

        $kilometers = DB::table('kilometers')
        ->get();

         return view('client.cars.create')
         ->with('vehicule', $vehicule)
         ->with('marques', $marques)
         ->with('carbirants',$carbirants)
         ->with('kilometers',$kilometers)
         ->with('origins',$origins);


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
        $car->marque_id = $request->marque_id;
        $car->model_id = $request->model_id;
        $car->year = $request->year;
        $car->carbirant_id= $request->carbirant_id;

          if($request->carte_grise_front ){

            Storage::disk('public_uploads')->delete('carte_grise/'.$car->carte_grise_front);
            Image::make($request->carte_grise_front)->resize(500,500, null, function ($constraint) {
            $constraint->aspectRatio();
           })->save(public_path('uploads/carte_grise/'.$request->carte_grise_front->hashName()));

            $car->carte_grise_front = $request->carte_grise_front->hashName();

         }

         if($request->carte_grise_back){

            Storage::disk('public_uploads')->delete('carte_grise/'.$car->carte_grise_back);
            Image::make($request->carte_grise_back)->resize(500,500, null, function ($constraint) {
            $constraint->aspectRatio();
           })->save(public_path('uploads/carte_grise/'.$request->carte_grise_back->hashName()));

            $car->carte_grise_back = $request->carte_grise_back->hashName();
         }

         if ($request->for_sale) {
            # code...
            $this->validate($request,[
                'title' => 'required',
                'fiscal_power' => 'required',
                'kilo' => 'required',
                'doors' => 'required',
                'origin_id' => 'required',
                'gear_box' => 'required',
                'first_hand' => 'required',
                'description' => 'required',
                'price' => 'required'
            ]);

            $request_data = [];

            if ($request->hasFile('images')) {
                foreach ( json_decode($car->images) as $image) {
                Storage::disk('public_uploads')->delete('cars_images/'.$image);
                }

                foreach ($request->images as $image) {
                    # code...
                    Image::make($image)->resize(1020, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(public_path('uploads/cars_images/'.$image->hashName()));

                    array_push($request_data,$image->hashName());
                 }

                 $car->images = json_encode($request_data);

            }

            $car->price = $request->price;
            $car->title=$request->title;
            $car->slug=Str::of($request->title)->slug('-');
            $car->for_sale=true;
            $car->fiscal_power=$request->fiscal_power;
            $car->kilometre_id=$request->kilo;
            $car->doors=$request->doors;
            $car->origin_id=$request->origin_id;
            $car->gearbox=$request->gear_box;
            $car->first_hand=($request->first_hand == "yes") ? true:false;
            $car->description=$request->description;
        }


       $car->update();
      return redirect()->route('cars.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        //


        Storage::disk('public_uploads')->delete('carte_grise/'.$car->carte_grise_back);
        Storage::disk('public_uploads')->delete('carte_grise/'.$car->carte_grise_front);

        if ($car->images) {
            # code...
            foreach ( json_decode($car->images) as $key=>$image) {
            Storage::disk('public_uploads')->delete('cars_images/'.$image[$key]);
            }
        }

        $car->delete();
        return redirect()->back();
    }


}
