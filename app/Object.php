<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Object extends Model
{
    protected $primaryKey = "ObjectID";

    protected $fillable = ['ObjectName', 'ObjectFinished'];

    public function itemWithdrawals(){
      return $this->hasMany('app\ItemWithdrawal', 'ItemWithdrawalID')->withDefault();
    }

    // public function scopeClient($query){
    //   return $query->where('ClientID', Auth::user()->ClientID);
    // }
}
