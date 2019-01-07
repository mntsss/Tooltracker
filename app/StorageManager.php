<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorageManager extends Model
{
    protected $fillable = ['storage_id', 'user_id'];

    public function assign(int $storage, int $user): void
    {
      $this->storage_id = $storage;
      $this->user_id = $user;
      $this->save();
    }

    public function storage(){
      return $this->belongsToOne('App\Storage', 'storage_id');
    }
    public function user(){
      return $this->belongsToOne('App\User', 'user_id');
    }
}
