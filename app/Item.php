<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Item extends Model
{
    protected $primaryKey = 'ItemID';

    protected $fillable = ['ItemName', 'ItemCode', 'ItemQuantity', 'ItemImage', 'ItemDeleted','ItemGroupID'];

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
    public function scopeDeleteItem($query){
      return $query->update(['ItemDeleted' => true]);
    }
    public function scopeExisting($query){
      return $query->where('ItemDeleted', false);
    }
}
