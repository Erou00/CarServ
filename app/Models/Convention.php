<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convention extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'no_convention',
        'ligne_budgetaire',
        'objet',
        'delais_execution',
        'delais_par',
        'ods',
        'fournisseur_id',
        'user_id',
        'magasin_id',
        'sous_magasin_id',
        'tva',
        'imp',
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


    public function conventionDetails()
    {
        # code...
        return $this->hasMany(ConventionDetail::class);
    }
    public function historiqueConventions()
    {
        # code...
        return $this->hasMany(HistoriqueConvention::class);
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
