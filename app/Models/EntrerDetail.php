<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrerDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'produit_id',
        'entrer_id',
        'qte',
        'puht',
        'tva',
        'prix_total',
        'magasin_id',
        'sous_magasin_id',
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

    public function sousMagasin()
    {
        # code...
        return $this->belongsTo(SousMagasin::class);
    }

    public function entrer()
    {
        # code...
        return $this->belongsTo(Entrer::class);
    }
    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }
}


