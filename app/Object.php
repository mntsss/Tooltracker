<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Object extends Model
{
    protected $primaryKey = "ObjectID";

    protected $fillable = ['ObjectName', 'ObjectFinished', 'UserID'];

    public function itemWithdrawals(){
      return $this->hasMany('App\ItemWithdrawal', 'ObjectID');
    }

    public function user(){
        return $this->hasOne('App\User', 'UserID', 'UserID');
    }
    // public function scopeClient($query){
    //   return $query->where('ClientID', Auth::user()->ClientID);
    // }
}
