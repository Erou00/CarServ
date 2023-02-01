<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueProduit extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['id','produit_id','user_id'];

    public function produit()
    {
        # code...
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

     //scope
     public function scopeWhenUserId($query, $userId)
     {
         return $query->when($userId, function ($q) use ($userId) {

             return $q->whereHas('user', function ($qu) use ($userId) {

                 return $qu->where('users.id', $userId);

             });

         });

     }// end of scopeWhenUserId

     public function scopeWhenProduitId($query, $produitId)
     {
         return $query->when($produitId, function ($q) use ($produitId) {

             return $q->whereHas('produit', function ($qu) use ($produitId) {

                 return $qu->where('produits.id', $produitId);

             });

         });

     }// end of scopeWhenProduitId
}
