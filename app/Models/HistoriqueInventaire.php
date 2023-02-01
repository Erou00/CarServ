<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueInventaire extends Model
{
    use HasFactory;
    protected $fillabel = ['id','invantaire_id','user_id'];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function inventaire()
    {
        # code...
        return $this->belongsTo(Inventaire::class);
    }

}
