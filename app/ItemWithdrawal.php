<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemWithdrawal extends Model
{
    protected $primaryKey = "ItemWithdrawalID";

    protected $fillable = ['ItemWithdrawalQuantity', 'ItemWithdrawalReturned', 'ItemWithdrawalReturnedQuantity', 'ItemID', 'UserID', 'ObjectID'];

    public function item(){
      return $this->belongsTo('App\Item', 'ItemID');
    }
    public function user(){
      return $this->belongsTo('App\Worker', 'UserID');
    }
    public function object(){
      return $this->belongsTo('App\Object', 'ObjectID');
    }
    public function scopeActive($query){
        return $query->where('ItemWithdrawalReturned', false);
    }

}
