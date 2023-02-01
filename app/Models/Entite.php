<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    use HasFactory;

    protected $fillable = ['id','abbreviation','nom','email','entite_mere_id','type_entite_id'];

    public $incrementing = false;

    public function entiteMere()
    {
        # code...
        return $this->belongsTo(Entite::class);
    }

    public function typeEntite()
    {
        # code...
        return $this->belongsTo(TypeEntite::class);
    }

    public function demande()
    {
        # code...
        return $this->hasMany(Demande::class);
    }

    public function bs()
    {
        # code...
        return $this->hasMany(Bs::class);
    }

    //scope
    public function scopeWhenEntiteId($query, $entiteId)
    {
        return $query->when($entiteId, function ($q) use ($entiteId) {

            return $q->whereHas('entiteMere', function ($qu) use ($entiteId) {

                return $qu->where('entites.entite_mere_id','like', $entiteId);

            });

        });

    }// end of scopeWhenCategorieId
}
