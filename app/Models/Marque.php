<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'nom','sous_categorie_id','timestamps'];


    public function sousCategorie()
    {

        return $this->belongsTo(SousCategorie::class);
    }

    public function produits()
    {
        # code...
        return $this->hasMany(Produit::class);
    }


        //scope
        public function scopeWhenSousCategorieId($query, $SousCategorieId)
        {
            return $query->when($SousCategorieId, function ($q) use ($SousCategorieId) {

                return $q->whereHas('sousCategorie', function ($qu) use ($SousCategorieId) {

                    return $qu->where('sous_categories.id', $SousCategorieId);

                });

            });

        }// end of scopeWhenCategorieId
}
