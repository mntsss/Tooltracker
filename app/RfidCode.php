<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RfidCode extends Model
{
    protected $primaryKey = "CodeID";

    protected $fillable = ['Code', 'ItemID'];

    public function item(){
      return $this->belongsTo('App\Item', 'ItemID');
    }
}
