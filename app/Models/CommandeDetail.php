<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'produit_id',
        'commande_id',
        'qte',
        'puht',
        'tva',
        'prix_total',
        'user_id',
        'timestamps'

    ];

    public function produit()
    {
        # code...
        return $this->belongsTo(Produit::class);
    }
    public function magasin()
    {
        # code...
        return $this->belongsTo(Magasin::class);
    }
    public function commande()
    {
        # code...
        return $this->belongsTo(Commande::class);
    }
    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }
}

