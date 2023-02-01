<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'utilisateur',
        'nom',
        'prenom',
        'email',
        'password',
        'magasin_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function categories()
    {
        # code...
        return $this->belongsToMany(Categorie::class);
    }
    //scope
    public function scopeWhenRolesId($query, $roleId)
    {
        return $query->when($roleId, function ($q) use ($roleId) {

            return $q->whereHas('roles', function ($qu) use ($roleId) {

                return $qu->where('roles.id', $roleId);
            });
        });
    } // end of scopeWhenRolesId


    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    //scope
    public function scopeWhenServiceId($query, $serviceId)
    {
        return $query->when($serviceId, function ($q) use ($serviceId) {

            return $q->whereHas('service', function ($qu) use ($serviceId) {

                return $qu->where('services.id', $serviceId);
            });
        });
    } // end of scopeWhenServicesId

    public function historiqueProduit()
    {
        # code...
        return $this->hasMany(HistoriqueProduit::class);
    }

    public function commandes()
    {
        # code...
        return $this->hasMany(Commande::class);
    }

    public function historiqueCommandes()
    {
        # code...
        return $this->hasMany(HistoriqueComande::class);
    }

    public function demandes()
    {
        # code...
        return $this->hasMany(Demande::class);
    }

    public function historiqueDemandes()
    {
        # code...
        return $this->hasMany(HistoriqueDemande::class);
    }


    public function conventions()
    {
        # code...
        return $this->hasMany(Convention::class);
    }

    public function marches()
    {
        # code...
        return $this->hasMany(Marche::class);
    }

    public function entrers()
    {
        # code...
        return $this->hasMany(Entrer::class);
    }



    public function bls()
    {
        # code...
        return $this->hasMany(Bl::class);
    }
    public function bs()
    {
        # code...
        return $this->hasMany(Bs::class);
    }


    public function factures()
    {
        # code...
        return $this->hasMany(Facture::class);
    }

    public function blDetails()
    {
        # code...
        return $this->hasMany(BlDetail::class);
    }

    public function bsDetails()
    {
        # code...
        return $this->hasMany(BsDetail::class);
    }

    public function inventaires()
    {
        # code...
        return $this->hasMany(Inventaire::class);
    }




    public function magasin()
    {
        # code...
        return $this->belongsTo(Magasin::class);
    }

    public function sousmagasins()
    {
        # code...
        return $this->belongsToMany(SousMagasin::class);
    }


}
