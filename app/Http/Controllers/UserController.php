<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use  Image;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show(User $user)
    {
        //
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


        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                        'required',
                         Rule::unique('users')->ignore($user->id),
                        ],
            'image' => 'image',
            'cin' => ['required', 'string', 'max:255'],
            'adress' => ['required', 'string', 'max:1000'],
            'phone_number' => ['required', 'string', 'max:10','min:10','unique:users'],


        ]);


        $request_data = $request->except(['image']);


        if ($request->image) {
            # code...

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/users_images/'.$request->image->hashName()));

            $request_data['image']=$request->image->hashName();
        }

        $user->update($request_data);
        session()->flash('success',"updated");

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        if ($user->image != 'default.png') {
            # code...
            Storage::disk('public_uploads')->delete('users_images/'.$user->image);
        }
        $user->delete();
        session()->flash('success','deleted');
        return redirect()->route('clients.index');
    }
}
