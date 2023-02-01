<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueCommande extends Model
{

    public $timestamps = true;
    protected $fillable = ['id','commande_id','user_id'];

    public function commande()
    {
        # code...
        return $this->belongsTo(Commande::class);
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

     public function scopeWhenCommandeId($query, $commandeId)
     {
         return $query->when($commandeId, function ($q) use ($commandeId) {

             return $q->whereHas('commande', function ($qu) use ($commandeId) {

                 return $qu->where('commandes.id', $commandeId);

             });

         });

     }// end of scopeWhenDemandeId

}
