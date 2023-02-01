<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'no_commande',
        'annee',
        'date_commande',
        'fournisseur_id',
        'user_id',
        'objet',
        'imp',
        'tva',
        'magasin_id',
        'sous_magasin_id',
        'timestamps'
    ];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);

    }

    public function fournisseur()
    {
        # code...
        return $this->belongsTo(Fournisseur::class);
    }

    public function marche()
    {
        # code...
        return $this->belongsTo(Marche::class);
    }

    public function commandeDetails()
    {
        # code...
        return $this->hasMany(CommandeDetail::class);
    }
    public function historiqueCommandes()
    {
        # code...
        return $this->hasMany(HistoriqueCommande::class);
    }

    public function bls()
    {
        # code...
        return $this->hasMany(Bl::class);
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
