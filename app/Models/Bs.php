<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bs extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'no_bl',
        'annee',
        'date',
        'demande_id',
        'sortie',
        'user_id',
        'entite_id',
        'magasin_id',
        'sous_magasin_id',
        'facture_id',
        'imp',
        'timestamps',

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

    public function facture()
    {
        # code...
        return $this->belongsTo(Facture::class);
    }

    public function entite()
    {
        # code...
        return $this->belongsTo(Entite::class);

    }


    public function bsDetails()
    {
        # code...
        return $this->hasMany(BsDetail::class);
    }

    public function historiqueBss()
    {
        # code...
        return $this->hasMany(HistoriqueBs::class);
    }

    public function be()
    {
        return $this->belongstoMany(Be::class);
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

}


