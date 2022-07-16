<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message','from_user_id','to_user_id','car_id','isSeen'];

    public function user()
    {
    return $this->belongsTo(User::class);
    }

    public function car()
    {
    return $this->belongsTo(Car::class);
    }
}
