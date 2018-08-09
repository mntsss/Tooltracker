<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemGroup extends Model
{
    protected $primaryKey = "ItemGroupID";

    protected $fillable = ['ItemGroupName', 'ItemGroupImage'];

    public function items(){
      return $this->hasMany('App\Item', 'ItemGroupID')->withDefault();
    }

    // public function scopeClient($query){
    //   return $query->where('ClientID', Auth::user()->ClientID);
    // }
}
