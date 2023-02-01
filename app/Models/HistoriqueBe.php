<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueBe extends Model
{
    use HasFactory;
    protected $fillable = ['id','be_id','user_id'];

    public function be()
    {
        # code...
        return $this->belongsTo(Be::class);
    }

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }
}
