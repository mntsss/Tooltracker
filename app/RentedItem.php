<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentedItem extends Model
{
  protected $primaryKey = "RentedItemID";

  protected $fillable = ['RentedItemName', 'RentedItemNote', 'ObjectID', 'RentedItemDailyPrice', 'RentedItemReturned', 'RentedItemDate'];

  public function scopeActive($query){
      return $query->where('RentedItemReturned', false);
  }

  public function cobject(){
    return $this->hasOne('App\CObject', 'ObjectID', 'ObjectID');
  }
}
