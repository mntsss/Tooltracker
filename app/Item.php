<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Item extends Model
{
    protected $primaryKey = 'ItemID';

    protected $fillable = ['ItemName', 'ItemCode', 'ItemQuantity', 'ItemImage', 'ItemGroupID'];

    public function itemGroup(){
      return $this->belongsTo('App\ItemGroup', 'ItemGroupID');
    }

    // public function scopeClient($query){
    //   return $query->where('ClientID', Auth::user()->ClientID);
    // }
}
