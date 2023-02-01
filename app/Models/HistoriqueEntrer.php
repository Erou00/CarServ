<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueEntrer extends Model
{
    use HasFactory;
    protected $fillable = ['id','entrer_id','user_id'];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

}
