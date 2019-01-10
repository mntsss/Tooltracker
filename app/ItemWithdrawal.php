<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemWithdrawal extends Model
{
    protected $primaryKey = "ItemWithdrawalID";

    protected $fillable = ['ItemWithdrawalQuantity', 'ItemWithdrawalReturned', 'ItemWithdrawalReturnedQuantity', 'ItemWithdrawalReturnConfirmedBy', 'ItemWithdrawalReturnConfirmCard', 'ItemID', 'UserID', 'ObjectID'];

    public function item(){
      return $this->hasOne('App\Item', 'id', 'ItemID');
    }
    public function image(){
        return $this->hasOne('App\ItemImage', 'ItemWithdrawalID', 'ItemWithdrawalID');
    }
    public function user(){
      return $this->hasOne('App\User', 'UserID');
    }
    public function object(){
      return $this->hasOne('App\CObject', 'ObjectID', 'ObjectID');
    }
    public function scopeActive($query){
        return $query->where('ItemWithdrawalReturned', false);
    }

}
