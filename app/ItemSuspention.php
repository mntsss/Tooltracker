<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSuspention extends Model
{
    protected $primaryKey = "SuspentionID";

    protected $fillable = ['SuspentionWarrantyFix', 'SuspentionUnwarrantedFix', 'SuspentionReturned', 'SuspentionNote', 'UserID','SuspentionConfirmCode', 'SuspentionWarningShowed', 'SuspentionUnconfirmedReturn', 'ItemID'];

    public function item(){
      return $this->belongsTo('App\Item', 'ItemID');
    }
    public function scopeActive($query){
        return $query->where('SuspentionReturned', false);
    }
}
