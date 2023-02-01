<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlDetail extends Model
{
    use HasFactory;

    protected $fillable =[
    'qte',
    'qte_livree',
    'bl_id',
    'magasin_id',
    'sous_magasin_id',
    'produit_id',
    'user_id',
    'timestamps'];

    public function bl()
    {
        # code...
        return $this->belongsTo(Bl::class);
    }
    public function produit()
    {
        # code...
        return $this->belongsTo(Produit::class);
    }

    public function magasin()
    {
        # code...
        return $this->belongsTo(Magasin::class);
    }

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }
}
