<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cmodel extends Model
{
    use HasFactory;

    protected $fillable = [
        'modelAbbr',
        'model',
        'MarqueID'
    ];

    public function cars()
    {
        # code..
        return $this->hasMany(Car::class);
    }

    public function mark()
    {
        # code..
        return $this->belongsTo(Mark::class);
    }
}
