<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrer extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'no_entrer',
        'annee',
        'no_bl',
        'date',
        'objet',
        'tva',
        'fournisseur_id',
        'user_id',
        'magasin_id',
        'sous_magasin_id',
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

    public function entrerDetails()
    {
        # code...
        return $this->hasMany(EntrerDetail::class);
    }
    public function historiqueEntrers()
    {
        # code...
        return $this->hasMany(HistoriqueEntrer::class);
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
