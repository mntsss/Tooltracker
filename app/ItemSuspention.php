<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSuspention extends Model
{
    protected $primaryKey = "SuspentionID";

    protected $fillable = ['SuspentionWarrantyFix', 'SuspentionUnwarrantedFix', 'SuspentionReturned', 'SuspentionNote', 'SuspentionWarningShowed', 'SuspentionUnconfirmedReturn', 'ItemID'];

    public function item(){
      return $this->belongsTo('App\Item', 'ItemID');
    }
}
