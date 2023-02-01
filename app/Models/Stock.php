<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'produit_id',
        'magasin_id',
        'old_qte',
        'qte',
        'user_id',
        'timestamps'


    ];


    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
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
}
