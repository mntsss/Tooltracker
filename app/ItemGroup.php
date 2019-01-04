<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ItemGroup extends Model
{
    protected $primaryKey = "ItemGroupID";

    protected $fillable = ['ItemGroupName', 'ItemGroupImage', 'storage_id'];

    public function items(){
      return $this->hasMany('App\Item', 'group_id');
    }
}
