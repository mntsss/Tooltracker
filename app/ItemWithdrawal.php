<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemWithdrawal extends Model
{
    protected $primaryKey = "ItemWithdrawalID";

    protected $fillable = ['ItemWithdrawalQuentity', 'ItemWithdrawalReturned', 'ItemWithdrawalReturnedQuantity', 'ItemID', 'WorkerID', 'ObjectID'];

    public function item(){
      return $this->belongsTo('App\Item', 'ItemID');
    }

    public function worker(){
      return $this->belongsTo('App\Worker', 'WorkerID')->withDefault();
    }
    public function object(){
      return $this->belongsTo('App\Object', 'ObjectID')->withDefault();
    }

    // public function scopeClient($query){
    //   return $query->where('ClientID', Auth::user()->ClientID);
    // }
}
