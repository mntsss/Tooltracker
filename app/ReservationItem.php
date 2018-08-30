<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    protected $primaryKey = 'ReservationItemID';

    protected $fillable = ['ReservationID', 'ItemID', 'ReservationItemQuantity'];

    public function reservation(){
      return $this->hasOne('App\Reservation', 'ReservationID', 'ReservationID');
    }

    public function item(){
      return $this->hasOne('App\Item', 'ItemID', 'ItemID');
    }
}
