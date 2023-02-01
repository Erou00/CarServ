<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['id', 'nom','categorie_id','timestamps'];

    public function fournisseurs()
    {

        return $this->hasMany(Fournisseur::class);
    }

    public function categorie()
    {

        return $this->belongsTo(Categorie::class);
    }

    public function produits()
    {
        # code...
        return $this->hasMany(Produit::class);
    }

    public function marques()
    {
        # code...
        return $this->hasMany(Marque::class);
    }


    //scope
    public function scopeWhenCategorieId($query, $categorieId)
    {
        return $query->when($categorieId, function ($q) use ($categorieId) {

            return $q->whereHas('categorie', function ($qu) use ($categorieId) {

                return $qu->where('categories.id', $categorieId);

            });

        });

    }// end of scopeWhenCategorieId
}

