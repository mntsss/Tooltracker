<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSuspention extends Model
{
    protected $primaryKey = "SuspentionID";

    protected $fillable = ['SuspentionWarrantyFix', 'SuspentionUnwarrantedFix', 'SuspentionReturned', 'SuspentionNote', 'UserID','SuspentionConfirmCode', 'SuspentionWarningShowed', 'SuspentionUnconfirmedReturn', 'ItemID'];

    public function item(){
      return $this->belongsTo('App\Item', 'id', 'ItemID');
    }
    public function user(){
      return $this->hasOne('App\User', 'id', 'UserID');
    }
    public function scopeActive($query){
        return $query->where('SuspentionReturned', false);
    }
}
