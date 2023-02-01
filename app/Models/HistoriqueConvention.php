<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueConvention extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['id','convention_id','user_id'];

    public function convention()
    {
        # code...
        return $this->belongsTo(Convention::class);
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

     public function scopeWhenConventionId($query, $conventionId)
     {
         return $query->when($conventionId, function ($q) use ($conventionId) {

             return $q->whereHas('convention', function ($qu) use ($conventionId) {

                 return $qu->where('conventions.id', $conventionId);

             });

         });

     }// end of scopeWhenDemandeId



}
