<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $primaryKey = 'ReservationID';

    protected $fillable = ['ReservationDelivered', 'ReservationRecipientUserID', 'ReservationConfirmSignature', 'ReservationConfirmCardNr', 'UserID', 'ObjectID'];

    public function items(){
      return $this->hasMany('App\ReservationItem', 'ReservationID');
    }
    public function user(){
      return $this->hasOne('App\User', 'id','UserID');
    }
    public function recipient(){
        return $this->hasMany('App\User', 'id', 'ReservationRecipientUserID');
    }
    public function cobject(){
      return $this->hasOne('App\CObject', 'ObjectID', 'ObjectID');
    }
    public function scopeActive($query){
        return $query->where('ReservationDelivered', false);
    }
}
