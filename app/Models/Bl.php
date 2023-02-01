<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bl extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'no_bl',
        'date',
        'retard',
        'facture_id',
        'commande_id',
        'marche_id',
        'fournisseur_id',
        'convention_id',
        'no_entrer',
        'annee',
        'imp',
        'user_id',
        'magasin_id',
        'sous_magasin_id',
        'timestamps'
    ];


    public function user()
    {
        # code...
        return $this->belongsTo(User::class);

    }

    public function commande()
    {
        # code...
        return $this->belongsTo(Commande::class);

    }

    public function convention()
    {
        # code...
        return $this->belongsTo(Convention::class);

    }

    public function marche()
    {
        # code...
        return $this->belongsTo(Marche::class);

    }

    public function facture()
    {
        # code...
        return $this->belongsTo(Facture::class);
    }

    public function blDetails()
    {
        # code...
        return $this->hasMany(BlDetail::class);
    }

    public function historiqueBls()
    {
        # code...
        return $this->hasMany(HistoriqueBl::class);
    }

    public function fournisseur()
    {
        # code...
        return $this->belongsTo(Fournisseur::class);

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
