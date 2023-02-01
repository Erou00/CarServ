<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;
    protected $fillable = ['id','no_commande','annee',
    'date_commande',
    'magasin_id',
    'sous_magasin_id',
    'entite_id','user_id','extern','imp','facture_id'];
    public $timestamps = true;


    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function entite()
    {
        # code...
        return $this->belongsTo(Entite::class);
    }

    public function demandeDetails()
    {
        # code...
        return $this->hasMany(DemandeDetail::class);
    }

    public function historiqueDemandes()
    {
        # code...
        return $this->hasMany(HistoriqueDemande::class);
    }

    public function bs()
    {
        # code...
        return $this->hasMany(Bs::class);
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

    public function facture()
    {
        # code...
        return $this->belongsTo(Facture::class);
    }
}
