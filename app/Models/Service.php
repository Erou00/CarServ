<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $table = 'services';
    public $timestamps = true;

    protected $fillable = ['id','nom'];


    public function users()
    {
        return $this->hasMany(User::class);
    }

}
