<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConventionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'produit_id',
        'convention_id',
        'qte',
        'puht',
        'tva',
        'prix_total',
        'user_id',
        'timestamps'

    ];

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

    public function convention()
    {
        # code...
        return $this->belongsTo(Convention::class);
    }


}
