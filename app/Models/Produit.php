<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'categorie_id',
        'sous_categorie_id',
        'marque_id',
        'designation',
        'prix_unitaire',
        'devise_id',
        'stock_min',
        'unite_reglementaire_id',
        'active',
        'groupe_id',
        'user_id',
        'timestamps'];


    public function categorie()
    {
        # code...
        return $this->belongsTo(Categorie::class);
    }

    public function sousCategorie()
    {
        # code...
        return $this->belongsTo(SousCategorie::class);
    }

    public function marque()
    {
        # code...
        return $this->belongsTo(Marque::class);
    }

    public function uniteReglementaire()
    {
        # code...
        return $this->belongsTo(UniteReglementaire::class);
    }

    public function devise()
    {
        # code...
        return $this->belongsTo(Devise::class);
    }

    public function historiquesProduit()
    {
        # code...
        return $this->hasMany(HistoriqueProduit::class);
    }

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function groupe()
    {
        # code...
        return $this->belongsTo(Groupe::class);
    }

    public function blDetails()
    {
        # code...
        return $this->hasMany(BlDetail::class);
    }

    public function entrerDetails()
    {
        # code...
        return $this->hasMany(EntrerDetail::class);
    }


    public function bsDetails()
    {
        # code...
        return $this->hasMany(BsDetail::class);
    }


    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function inventaireDetails()
    {
        return $this->hasMany(InventaireDetail::class);
    }

}
