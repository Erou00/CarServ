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
                'fiscal_power' => 'required',
                'kilo' => 'required',
                'doors' => 'required',
                'origin_id' => 'required',
                'gear_box' => 'required',
                'first_hand' => 'required',
            ]);

            $car->for_sale=true;
            $car->fiscal_power=$request->fiscal_power;
            $car->kilometres=$request->kilo;
            $car->doors=$request->doors;
            $car->origin=$request->origin_id;
            $car->gearbox=$request->gear_box;
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
                'fiscal_power' => 'required',
                'kilo' => 'required',
                'doors' => 'required',
                'origin_id' => 'required',
                'gear_box' => 'required',
                'first_hand' => 'required',
            ]);

            $car->for_sale=true;
            $car->fiscal_power=$request->fiscal_power;
            $car->kilometres=$request->kilo;
            $car->doors=$request->doors;
            $car->origin=$request->origin_id;
            $car->gearbox=$request->gear_box;
            $car->first_hand=($request->first_hand == "yes") ? true:false;
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


        $car->delete();
        return redirect()->back();
    }

    public function models(Request $request)
    {
        //
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        $data = DB::table('cmodels')
          ->where('MarqueId',$value)
          ->get();

        // Select '.ucfirst($dependent).
        $output = '<option value="">Choose</option>';
        foreach($data as $row)
        {
         $output .= '<option value="'.$row->id.'">'.$row->$dependent.'</option>';
        }
        echo $output;
    }
}
