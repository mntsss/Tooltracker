<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['item_id', 'user_id', 'object_id', 'storage_id', 'previous_status', 'current_status',
      'signature', 'identification_code'];

    public function item(){
      return $this->hasOne('App\Item', 'id', 'item_id');
    }

    public function cobject(){
      return $this->hasOne('App\CObject', 'object_id');
    }

    public function user(){
      return $this->hasOne('App\User', 'id','user_id');
    }

    public function storage(){
        return $this->hasOne('App\Storage', 'id','storage_id');
    }
}
