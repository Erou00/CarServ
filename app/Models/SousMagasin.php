<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousMagasin extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['id', 'nom','magasin_id','timestamps'];


    public function magasin()
    {

        return $this->belongsTo(Magasin::class);
    }


    public function users()
    {
        # code...
        return $this->belongsToMany(User::class);
    }


    //scope
    public function scopeWhenMagasinId($query, $magasinId)
    {
        return $query->when($magasinId, function ($q) use ($magasinId) {

            return $q->whereHas('magasin', function ($qu) use ($magasinId) {

                return $qu->where('magasins.id', $magasinId);

            });

        });

    }// end of scopeWhenmagasinId

    public function commandes()
    {
        # code...
        return $this->hasMany(Commande::class);
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

    public function entrerDetails()
    {
        # code...
        return $this->hasMany(EntrerDetail::class);
    }
}

