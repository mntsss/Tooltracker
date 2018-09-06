<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemWithdrawal extends Model
{
    protected $primaryKey = "ItemWithdrawalID";

    protected $fillable = ['ItemWithdrawalQuantity', 'ItemWithdrawalReturned', 'ItemWithdrawalReturnedQuantity', 'ItemWithdrawalReturnConfirmedBy', 'ItemWithdrawalReturnConfirmCard', 'ItemID', 'UserID', 'ObjectID'];

    public function item(){
      return $this->hasOne('App\Item', 'ItemID', 'ItemID');
    }
    public function image(){
        return $this->hasOne('App\ItemImage', 'ItemWithdrawalID', 'ItemWithdrawalID');
    }
    public function user(){
      return $this->belongsTo('App\User', 'UserID');
    }
    public function object(){
      return $this->belongsTo('App\Object', 'ObjectID');
    }
    public function scopeActive($query){
        return $query->where('ItemWithdrawalReturned', false);
    }

}
