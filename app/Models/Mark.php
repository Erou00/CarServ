<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','logo',

    ];

    public function cars()
    {
        # code..
        return $this->hasMany(Car::class);
    }

    public function cmodels()
    {
        # code..
        return $this->hasMany(Cmodel::class);
    }
}
