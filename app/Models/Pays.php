<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{

    protected $table = 'pays';
    public $timestamps = true;
    protected $fillable = array('nom', 'timestamps');

    public function villes()
    {
        return $this->hasMany(Ville::class);
    }
}
