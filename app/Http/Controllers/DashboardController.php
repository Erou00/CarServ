<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\Marche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        # code..

        $fournisseurs = Fournisseur::all();
        return view('dashboard.index',compact('fournisseurs'));

    }
}
