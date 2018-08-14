<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $primaryKey = 'ReservationID';

    protected $fillable = ['ReservationDelivered', 'ReservationReceivedByUserID', 'UserID', 'ObjectID'];

    public function items(){
      return $this->hasMany('App\ReservationItem', 'ReservationID');
    }
}
