<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $fillable = ['name', 'item_id', 'reservation_id', 'withdrawal_id', 'reservation_item_id'];

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
