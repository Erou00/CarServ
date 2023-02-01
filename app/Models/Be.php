<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Be extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'no_sortie',
        'annee',
        'designation',
        'date',
        'user_id',
        'magasin_id',
        'sous_magasin_id',
        'imp',
        'timestamps',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bs()
    {
        return $this->belongstoMany(Bs::class);
    }

    public function historiqueBes()
    {
        # code...
        return $this->hasMany(HistoriqueBe::class);
    }

    public function magasin()
    {
        # code...
        return $this->belongsTo(Magasin::class);
    }

    public function sousMagasin()
    {
        # code...
        return $this->belongsTo(SousMagasin::class);
    }
}
