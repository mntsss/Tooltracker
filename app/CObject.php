<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

/////////////
// Had to be renamed from Object because of PHP7.2 special names rule. Stands for construction objects...
////////////
class CObject extends Model
{
    protected $table = 'objects';
    protected $primaryKey = "ObjectID";

    protected $fillable = ['ObjectName', 'ObjectFinished'];

    public function itemWithdrawals(){
      return $this->hasMany('App\ItemWithdrawal', 'ObjectID');
    }
    public function foremen(){
      return $this->hasMany('App\ObjectForeman', 'ObjectID', 'ObjectID');
    }
    public function rented(){
      return $this->hasMany('App\RentedItem', 'ObjectID', 'ObjectID');
    }
    public function rentedItems(){
      return $this->hasMany('App\RentedItem', 'ObjectID', 'ObjectID');
    }
}
