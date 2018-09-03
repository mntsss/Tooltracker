<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

/////////////
// Had to be renamed from Object because of PHP7.2 special names rule. Stands for construction objects...
////////////
class CObject extends Model
{
    protected $table = 'objects';
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