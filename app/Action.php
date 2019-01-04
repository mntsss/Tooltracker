<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['item_id', 'user_id', 'object_id', 'storage_id', 'previous_status', 'current_status',
      'signature', 'identification_code'];

    public function item(){
      $this->hasOne('App\Item', 'item_id');
    }

    public function cobject(){
      $this->hasOne('App\CObject', 'object_id');
    }

    public function user(){
      $this->hasOne('App\User', 'user_id');
    }
}
