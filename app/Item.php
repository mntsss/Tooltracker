<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Item extends Model
{
    protected $primaryKey = 'ItemID';

    protected $fillable = ['ItemName', 'ItemCode', 'ItemQuantity', 'ItemImage', 'ItemGroupID'];

    public function itemGroup(){
      return $this->belongsTo('App\ItemGroup', 'ItemGroupID');
    }
    public function withdrawals(){
      return $this->hasMany('App\ItemWithdrawal', 'ItemID');
    }
    public function lastWithdrawal(){
      return $this->hasOne('App\ItemWithdrawal', 'ItemID')->latest();
    }
    public function lastSuspention(){
      return $this->hasOne('App\ItemSuspention', 'ItemID')->latest();
    }
    public function lastReservation(){
      return $this->hasOne('App\ReservationItem', 'ItemID')->latest();
    }
}
