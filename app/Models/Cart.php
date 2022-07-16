<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public $table = 'carts';
    protected $fillable = [

        'product','product_id','quantity','price','user_id'
    ];


    public function products()
    {
        # code...
       return  $this->hasMany(Product::class,'id','product_id');
    }

}
