<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kilometer extends Model
{
    use HasFactory;

    protected $fillable= ["kilometers"];

    public function cars(){

        return $this->hasMany(Car::class);
    }
}
