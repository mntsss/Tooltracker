<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemGroup extends Model
{
    protected $primaryKey = "ItemGroupID";

    protected $fillable = ['ItemGroupName', 'ItemGroupImage', 'ReservationItemID'];

    public function items(){
      return $this->hasMany('App\Item', 'ItemGroupID');
    }

    // public function scopeClient($query){
    //   return $query->where('ClientID', Auth::user()->ClientID);
    // }
}
