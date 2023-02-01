<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEntite extends Model
{
    use HasFactory;

    protected $table = 'type_entites';
    public $timestamps = true;

    protected $fillable = ['id','type'];

    public function entites()
    {
        # code...
        return $this->hasMany(Entite::class);
    }
}
