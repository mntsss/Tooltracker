<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $primaryKey = "UserID";
    protected $fillable = [
        'email', 'Username', 'UserPhone','password', 'UserLastSeen', 'UserRole', 'UserDeleted', 'UserRFIDCode'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeExisting($query){
      return $query->where('UserDeleted', false);
    }
    public function scopeGetDeleted($query){
      return $query->where('UserDeleted', true);
    }
    public function suspentions(){
        return $this->hasMany('App\ItemSuspention', 'UserID');
    }

    public function withdrawals(){
        return $this->hasMany('App\ItemWithdrawal', 'UserID');
    }
    public function reservations(){
        return $this->hasMany('App\Reservation', 'UserID');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
