<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueBs extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['id','bs_id','user_id'];

    public function bs()
    {
        # code...
        return $this->belongsTo(Bs::class);
    }

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }
}
