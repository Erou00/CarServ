<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueMarche extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['id','marche_id','user_id'];

    public function marche()
    {
        # code...
        return $this->belongsTo(Marche::class);
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

     public function scopeWhenMarcheId($query, $marcheId)
     {
         return $query->when($marcheId, function ($q) use ($marcheId) {

             return $q->whereHas('marche', function ($qu) use ($marcheId) {

                 return $qu->where('marches.id', $marcheId);

             });

         });

     }// end of scopeWhenDemandeId



}
