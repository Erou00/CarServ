<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventaireDetail extends Model
{
    use HasFactory;


    protected $fillable = [
    'produit_id',
    'inventaire_id',
    'lot',
    'date_premption',
    'qte',
    'qte_stock',
    'magasin_id',
    'stock_id',
    'user_id'];

    public function user()
    {
        # code..
        return $this->belongsTo(User::class);
    }

    public function inventaire()
    {
        # code..
        return $this->belongsTo(Inventaire::class);
    }

    public function produit()
    {
        # code..
        return $this->belongsTo(Produit::class);
    }

    public function magasin()
    {
        # code..
        return $this->belongsTo(Magasin::class);
    }

}
