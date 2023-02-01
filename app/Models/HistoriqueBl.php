<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueBl extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['id','bl_id','user_id'];

    public function bl()
    {
        # code...
        return $this->belongsTo(Bl::class);
    }

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }
}
