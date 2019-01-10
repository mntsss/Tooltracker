<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Item extends Model
{
    /**
     * Item status constants
     */
    public const ITEM_IN_STORAGE = "storage";
    public const ITEM_DELETED = "deleted";
    public const ITEM_WARRANTED_FIX = "warranted_fix";
    public const ITEM_UNWARRANTED_FIX = "unwarranted_fix";
    public const ITEM_WAITING_CONFIRMATION = "waiting_confirmation";
    public const ITEM_IN_USE = "in_use";
    public const ITEM_RESERVED = "reserved";

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'acquired_from', 'warranty_date', 'purchase_date', 'consumable', 'deleted', 'group_id', 'storage_id', 'identification'];


    public function setInStorage(){
        $this->status = self::ITEM_IN_STORAGE;
    }

    public function setDeleted(){
        $this->status = self::ITEM_DELETED;
    }

    public function setWarrantedFix(){
        $this->status = self::ITEM_WARRANTED_FIX;
    }

    public function setUnwarrantedFix(){
        $this->status = self::ITEM_UNWARRANTED_FIX;
    }

    public function setWaitingConfirmation(){
        $this->status = self::ITEM_WAITING_CONFIRMATION;
    }

    public function setInUse(){
        $this->status = self::ITEM_IN_USE;
    }
    public function setReserved(){
        $this->status = self::ITEM_RESERVED;
    }

    /**
     * Scopes
     */

    public function scopeSuspended($query){
        return $query->where('status', self::ITEM_UNWARRANTED_FIX)->orWhere('status', self::ITEM_WARRANTED_FIX)->orWhere('status', self::ITEM_WAITING_CONFIRMATION);
    }
    public function scopeDeleted($query){
        return $query->where('status', self::ITEM_DELETED)->orderBy('updated_at', 'DESC');
    }
    public function scopeExisting($query){
        return $query->where('status', "!=", self::ITEM_DELETED);
    }

    /**
     *  Relations
     */

    public function itemGroup(){
      return $this->belongsTo('App\ItemGroup', 'group_id');
    }

    public function codes(){
      return $this->hasMany('App\Code', 'ItemID');
    }

    public function images(){
        return $this->hasMany('App\ItemImage')->orderBy('created_at', 'DESC');
    }
    public function withdrawals(){
      return $this->hasMany('App\ItemWithdrawal', 'ItemID');
    }
    public function suspentions(){
      return $this->hasMany('App\ItemSuspention', 'ItemID');
    }
    public function reservations(){
      return $this->hasMany('App\ReservationItem', 'ItemID');
    }
    public function lastWithdrawal(){
      return $this->hasOne('App\ItemWithdrawal', 'ItemID')->latest();
    }
    public function lastSuspention(){
      return $this->hasOne('App\ItemSuspention', 'ItemID')->latest();
    }
    public function lastReservation(){
      return $this->hasOne('App\ReservationItem', 'ItemID')->latest();
    }
}
