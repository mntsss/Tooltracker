<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObjectForeman extends Model
{
    protected $primaryKey = "ObjectForemanID";

    protected $fillable = ['ObjectID', 'UserID', 'ForemanRemoved'];

    public function cobject(){
        return $this->belongsTo('App\CObject', 'ObjectID');
    }

    public function user(){
        return $this->hasOne('App\User', 'id','UserID');
    }

    public function scopeActive($query){
        return $query->where('ForemanRemoved', false);
    }
}
