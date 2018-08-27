<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $primaryKey = 'ImageID';

    protected $fillable = ['ImageName', 'ItemID', 'ReservationID', 'ItemWithdrawalID'];

    public function item(){
      return $this->hasOne('App\Item', 'ItemID');
    }
    public function reservation(){
      return $this->hasOne('App\Reservation', 'ReservationID');
    }
    public function withdrawal(){
      return$this->hasOne('App\ItemWithdrawal', 'ItemWithdrawalID');
    }
}
