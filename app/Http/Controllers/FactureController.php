<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $factures = Facture::all();
        return view('dashboard.factures.index',compact('factures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('dashboard.factures.create');
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
            'n_facture'  => 'required',
            'n_pv'  => 'required',
            'montant'  => 'required',
            'date_depot'  => 'required',
            'n_registre'  => 'required',
            ]);


        $facture_data = $request->all();
        $facture_data['user_id'] = Auth::id();
        Facture::create($facture_data);

        return redirect()->route('factures.index');

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
        $facture = Facture::findOrfail($id);
        return view('dashboard.factures.edit',compact('facture'));
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
        $request->validate([
            'n_facture'  => 'required',
            'n_pv'  => 'required',
            'montant'  => 'required',
            'date_depot'  => 'required',
            'n_registre'  => 'required',
            ]);

        $facture = Facture::findOrfail($id);
        $facture->update($request->all());

        return redirect()->route('factures.index');

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
        $facture = Facture::findOrfail($id);
        $facture->delete();

        session()->flash('success', __('Supprimé avec succès'));
        return response(__('Supprimé avec succès'));
    }

    public function getFacture()
    {
        # code...
        $factures = Facture::all();
       return response()->json([
            'error' =>false,
            'factures' => $factures
        ]);

    }
    public function addFacture(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'n_facture'  => 'required',
            'n_pv'  => 'required',
            'montant'  => 'required',
            'date_depot'  => 'required',
            'n_registre'  => 'required',
       ]);

        if ($validator->fails()) {
             return response()->json($validator->messages(), 400);
        }

            $facture_data = $request->all();
            $facture_data['user_id'] = Auth::id();
            Facture::create($facture_data);

            return response()->json([
                'error' => false,
            ],200);

    }
}
