<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Worker extends Model
{
    protected $primaryKey = "WorkerID";

    protected $fillable = ['WorkerName', 'WorkerCode'];

    public function itemWithdrawals(){
      return $this->hasMany('App\ItemWithdrawal', 'ItemWithdrawalID')->withDefault();
    }

    // public function scopeClient($query){
    //   return $query->where('ClientID', Auth::user()->ClientID);
    // }
}
