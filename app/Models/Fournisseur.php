<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseurs';
    public $timestamps = true;
    protected $fillable = ['id', 'ville_id', 'nom', 'representant', 'adresse',
    'telephone', 'fax', 'email','siteweb','patente',];

    public function categorieFournisseur()
    {
        return $this->belongsTo(CategorieFournisseur::class);
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

        //scope
        public function scopeWhenVilleId($query, $villeId)
        {
            return $query->when($villeId, function ($q) use ($villeId) {

                return $q->whereHas('villes', function ($qu) use ($villeId) {

                    return $qu->where('villes.id', $villeId);

                });

            });

        }// end of scopeWhenGenreId



        public function commandes()
        {
            # code...
            return $this->hasMany(Commande::class);
        }

        public function marches()
        {
            # code...
            return $this->hasMany(Marche::class);
        }

        public function conventions()
        {
            # code...
            return $this->hasMany(Convention::class);
        }
        public function bls()
        {
            # code...
            return $this->hasMany(Bl::class);
        }

        public function entrers()
    {
        # code...
        return $this->hasMany(Entrer::class);
    }

}
