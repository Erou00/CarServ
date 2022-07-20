<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Image;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roles = Role::where('name','=','mecanicien')->first();
        $mechanics = $roles->users()->get();
        return view('dashboard.mechanics.index')->with('mecaniciens',$mechanics);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('dashboard.mechanics.create');
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'cin' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'adress' => ['required', 'string', 'max:1000'],
            'phone_number' => ['required', 'string', 'max:10','max:10','unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ]);


        //dd($request->all());
        $mecanicien = new User;




        $mecanicien->last_name = $request->last_name;
        $mecanicien->first_name = $request->first_name;
        $mecanicien->cin = $request->cin;
        $mecanicien->email = $request->email;
        $mecanicien->adress = $request->adress;
        $mecanicien->phone_number = $request->phone_number;

        if($request->hasfile('image')){
            Image::make($request->image)
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/meachincs_images/' . $request->image->hashName()));

            $mecanicien->image = $request->image->hashName();
       }
        $mecanicien->password = Hash::make($request->password);

        $mecanicien->save();
        $role = Role::select('id')->where('name','mecanicien')->first();
        $mecanicien -> roles() -> attach($role);


        toastr()->success('Success Message');
        return redirect()->route('dashboard.mechanics.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $mechanic = User::find($id);
        //dd($date);



        $demandes = Demande::with('mechanic')
        ->with('car',function($query)
        {
            # code...
            $query->with('marque')->with('model');
        })
        ->with('services')
        ->where('mechanic_id',$mechanic->id)->orderByDesc('date')->get();


        // dd($demandes);
        return view('dashboard.mechanics.details')->with(["demandes"=>$demandes,'mechanic'=>$mechanic]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        return view('dashboard.mechanics.create')->with('mecanicien',$user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $user = User::find($id);
        $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->cin = $request->cin;
            $user->email = $request->email;
            $user->adress = $request->adress;
            $user->phone_number = $request->phone_number;

        $user->update();

        toastr()->success('Mechanic updated Successfully');
        return redirect()->route('dashboard.mechanics.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

         $user = User::find($id);
            $demandes =  Demande::where('mechanic_id',$id)->get();

            if ($demandes->count() > 0) {
                # code...
                foreach ($demandes as $d) {
                    # code...
                if ($d->etat == 'Affected') {

                    $d->etat = 'In progress';
                    $d->mecanicien_id= null;
                    $d->update();

                    }
                elseif($d->etat != 'Affected'){
                    $d->update(['mecanicien_id'=>null]);
                }
                }


            }

        if ($user->image != 'default.png') {
            # code...
            Storage::disk('public_uploads')->delete('users_images/'.$user->image);
        }
        $user->delete();
        toastr()->success('Mechanic deleted Successfully');
        return redirect()->back();
    }


    public function mecanicienVidanges($id)
    {
        # code...
        // $mecanicien = User::find($id);
        // //dd($date);



        // $vidanges = Vidange::with('mecanicien')
        // ->with('vehicule',function($query)
        // {
        //     # code...
        //     $query->with('marque')->with('model');
        // })
        // ->with('products',function($query)
        // {
        //     # code...
        //     $query->with('type');
        // })->where('mecanicien_id',$mecanicien->id)->orderByDesc('date')->get();
    }
}
