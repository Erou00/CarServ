<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = ['n_facture','n_pv','montant','date_depot','n_registre'];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function bl()
    {
        # code...
        return $this->belongsTo(Bl::class);
    }

    public function demande()
    {
        # code...
        return $this->belongsTo(Demande::class);
    }
}
