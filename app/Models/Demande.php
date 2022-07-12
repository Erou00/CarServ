<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'date',
        'address',
        'commente',
        'motif',
        'user_id',
        'admin_id',
        'mecanicien_id'

    ];

    public function car()
    {
        # code..
        return $this->belongsTo(Car::class);
    }


    public function user()
    {
        # code..
        return $this->belongsTo(User::class);
    }

    public function mechanic()
    {
        # code..
        return $this->belongsTo(User::class);
    }


    public function services()
    {
        # code...
        return $this->belongsToMany(Service::class);
    }
}
