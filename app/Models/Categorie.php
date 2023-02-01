<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{

    protected $table = 'categories';
    public $timestamps = true;
    protected $fillable = ['id', 'nom', 'timestamps'];

    public function souscategories()
    {
        # code...
        return $this->hasMany(Categorie::class);
    }

    public function produits()
    {
        # code...
        return $this->hasMany(Produit::class);
    }

    public function users()
    {
        # code...
        return $this->belongsToMany(User::class);
    }

}
