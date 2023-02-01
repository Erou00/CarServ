<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeDetail extends Model
{
    use HasFactory;


    protected $fillable = [
        'demande_id',
        'produit_id',
        'qte_demandee'
    ];



    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function demande()
    {
        # code...
        return $this->belongsTo(Demande::class);
    }

    public function produit()
    {
        # code...
        return $this->belongsTo(Produit::class);
    }

}
