<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = ['id','nom'];

    public function produits()
    {
        # code...
        return $this->hasMany(Produit::class);
    }
}
