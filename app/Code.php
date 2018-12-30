<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = ['code', 'item_id'];

    public function item(){
      return $this->belongsTo('App\Item', 'item_id');
    }
}
