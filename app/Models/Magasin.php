<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magasin extends Model
{
    use HasFactory;

    protected $table = 'magasins';
    public $timestamps = true;

    protected $fillable = ['id','nom'];

    public function blDetails()
    {
        # code...
        return $this->hasMany(BlDetail::class);
    }

    public function bsDetails()
    {
        # code...
        return $this->hasMany(BsDetail::class);
    }

    public function stock()
    {
        # code...
        return $this->hasMany(Stock::class);
    }

    public function user()
    {
        # code...
        return $this->hasMany(User::class);
    }

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

    public function sousmagasins()
    {
        # code...
        return $this->hasMany(SousMagasin::class);
    }
}
