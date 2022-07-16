<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque_id',
        'model_id',
        'year',
        'carbirant_id',
        'kilometres',
        'title',
        'fiscal_power',
        'gearbox',
        'doors',
        'origin',
        'first_hand',
        'for_sale',
        'carte_grise_front',
        'carte_grise_back',
        'description',
        'image',
        'price',
        'user_id',
        'admin_id',
        'mechanic_id',
        'slug'

    ];


    public function user()
    {
        # code..
        return $this->belongsTo(User::class);
    }

    public function marque()
    {
        # code..
        return $this->belongsTo(Mark::class);
    }

    public function model()
    {
        # code..
        return $this->belongsTo(Cmodel::class);
    }

    public function carbirant()
    {
        # code..
        return $this->belongsTo(Carbirant::class);
    }

    public function origin()
    {
        # code..
        return $this->belongsTo(Origin::class);
    }

    public function kilometre()
    {
        # code..
        return $this->belongsTo(kilometer::class);
    }

    public function demandes()
    {
        # code...
        return $this->hasMany(Demande::class);
    }

    public function messages()
    {
        # code...
        return $this->hasMany(Message::class);
    }

}
