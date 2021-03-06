<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'last_name',
        'first_name',
        'cin',
        'email',
        'adress',
        'phone_number',
        'image',
        'password',
    ];

    protected $appends = ['image_path','avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function roles()
    {
        # code...
        return $this->belongsToMany('App\Models\Role');
    }



    public function hasAnyRoles($roles)
    {
        # code...
        if($this->roles()->whereIn('name',$roles)->first()){
            return true;
        }
        return false;
    }

    public function hasRole($role)
    {
        # code...
        if($this->roles()->where('name',$role)->first()){
            return true;
        }
        return false;
    }


    public function getFirstNameAttribute($value)
    {
        # code...
        return ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        # code...
        return ucfirst($value);
    }

    public function getImagePathAttribute()
    {
        # code...
        return asset('uploads/users_images/'.$this->image);
    }

    public function cars()
    {
        # code..
        return $this->hasMany(Car::class);
    }

    public function demandes()
    {
        # code..
        return $this->hasMany(Demande::class);
    }

    public function Mdemandes()
    {
        # code..
        return Demande::where('mechanic_id',$this->id)->get();
    }

    public function MAdemandes()
    {
        # code..
        return Demande::where('mechanic_id',$this->id)->where('etat','Affected')->get();
    }

    public function MACdemandes()
    {
        # code..
        return Demande::where('mechanic_id',$this->id)->where('etat','Completed')->get();
    }

    public function messages()
    {
        # code..
        return $this->hasMany(Message::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }



    public function getAvatarAttribute()
    {

        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) );


    }

}
