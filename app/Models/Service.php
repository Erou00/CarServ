<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [

        'image',
        'name',
        'home_service',

    ];

    protected $appends = ['image_path'];


    public $timestamps = true;



    protected $dates = ['deleted_at'];


    public function getImagePathAttribute()
    {
        # code...
        return asset('uploads/services_images/'.$this->image);
    }
}


