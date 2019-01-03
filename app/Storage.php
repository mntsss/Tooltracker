<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $fillable = ['name', 'deleted'];

    public function groups()
    {
      return $this->hasMany('App\ItemGroup', 'storage_id');
    }

    public function scopeActive(){
      return $query->where('deleted', false);
    }

    public function rename(string $newName):void
    {
      $this->name = $newName;
      $this->save();
    }

    public function delete():void
    {
      $this->deleted = true;
      $this->save();
    }
}
