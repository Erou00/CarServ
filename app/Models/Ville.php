<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use HasFactory;

    protected $table = 'villes';
    public $timestamps = true;
    protected $fillable = ['id', 'nom','pays_id', 'timestamps'];



    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function fournisseur()
    {
        return $this->hasMany(Fournisseur::class);
    }

     //scope
     public function scopeWhenPaysId($query, $paysId)
     {
         return $query->when($paysId, function ($q) use ($paysId) {

             return $q->whereHas('pays', function ($qu) use ($paysId) {

                 return $qu->where('pays.id', $paysId);

             });

         });

     }// end of scopeWhenGenreId

}
