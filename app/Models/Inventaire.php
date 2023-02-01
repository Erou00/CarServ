<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    use HasFactory;
    protected $fillable =  [
        'id',
        'no_inventaire',
        'etat',
        'date_verification',
        'date_preparation',
        'date_validation',
        'magasin_id',
        'user_id',
    ];


    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function inventaireDetails()
    {
        # code...
        return $this->hasMany(InventaireDetail::class);
    }

    public function historiqueInventaires()
    {
        # code...
        return $this->hasMany(HistoriqueInventaire::class);
    }
}
