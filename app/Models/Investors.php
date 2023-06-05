<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Investors extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'gender',
        'address',
        'birth_date',
        'location_id',
        'nik',
        'npwp',
        'identity_photo',
        'bank_id',
        'register_date',
        'balance',
    ];
    // protected $table = 'investors';
    
    // protected $guarded = [];

    /**
     * That attributes that should be hidden for arrays.
     * 
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /** 
     * The attributes that should be cast to native types
     * 
     * @var array 
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'balance' => 'integer',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * 
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    } 
    /**
     * Return a key value array, containing any custom claims to be added to the JWt.
     * 
     * @return array
     */
    public function getJWTCustomClaims() {
        return[];
    }

    public function tim()
    {
        return $this->belongsTo(FishermanTim::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // JWTSubject implementation
    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    // // JWTSubject implementation
    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }
}
