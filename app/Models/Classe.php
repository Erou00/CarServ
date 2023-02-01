<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $table = 'classes';
    public $timestamps = true;
    protected $fillable = ['id','volume_id', 'nom','code', 'timestamps'];


public function volume()
{
    # code...
    return $this->belongsTo(Volume::class);
}


 //scope
 public function scopeWhenVolumeId($query, $volumeId)
 {
    return $query->when($volumeId, function ($q) use ($volumeId) {

        return $q->whereHas('volume', function ($qu) use ($volumeId) {

            return $qu->where('volumes.id', $volumeId);

        });

    });
 }// end of scopeWhenGenreId
}
