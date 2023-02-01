<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classe;

class Volume extends Model
{

    protected $table = 'volumes';
    public $timestamps = true;
    protected $fillable = ['id', 'nom','code','timestamps'];


    public function classes()
    {
        # code...
        return $this->hasMany(Classe::class);
    }
}
