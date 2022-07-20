<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Demande;
use App\Models\Message;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roles = Role::where('name','=','client')->first();
        $clients = $roles->users()->get();

        return view('dashboard.clients.index')->with('clients',$clients);
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $client = User::find($id);
        return view('dashboard.clients.client-details',['user'=>$client]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
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

        $car = Car::where('user_id',$user->id)->get();

        if ($car->count() > 0) {
            # code...
            $demande = Demande::where('car_id',$car->last()->id)->get();

            if ($demande->count() > 0) {
                # code...
                $message = Message::where('car_id',$demande->last()->id)->get();
                $message->each->delete();
            }

            $demande->each->delete();
            $car->each->delete();
        }


        if ($user->image != 'default.png') {
            # code...
            Storage::disk('public_uploads')->delete('users_images/'.$user->image);
        }

        $user -> roles() -> detach();
        $user->delete();

        toastr()->success('Client deleted Successfully');


        return redirect()->route('dashboard.clients.index');
    }

    public function AllClients()
    {
        # code...
        $roles = Role::where('name','=','client')->first();

        return  $roles->users()->get();

    }
}
