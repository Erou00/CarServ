<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniteReglementaire extends Model
{
    use HasFactory;

    protected $table = 'unite_reglementaires';
    public $timestamps = true;
    protected $fillable = ['id', 'code', 'designation','timestamps'];


    public function produits()
    {
        # code...
        return $this->hasMany(Produit::class);
    }
}
