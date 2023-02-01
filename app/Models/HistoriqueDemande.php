<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueDemande extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['id','demande_id','user_id'];

    public function demande()
    {
        # code...
        return $this->belongsTo(Demande::class);
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

     public function scopeWhenDemandeId($query, $demandeId)
     {
         return $query->when($demandeId, function ($q) use ($demandeId) {

             return $q->whereHas('demande', function ($qu) use ($demandeId) {

                 return $qu->where('demandes.id', $demandeId);

             });

         });

     }// end of scopeWhenDemandeId

}
