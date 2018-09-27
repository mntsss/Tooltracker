<?php
namespace App\Traits;
use App\Item;
use App\ItemGroup;
trait ItemInfo{

  public function GetItemState(Item $item){

    if($item->ItemDeleted){
      return "Ištrintas";
    }
    if($item->ItemConsumable){
      return "Sandėlyje";
    }
    if(!is_null($item->lastSuspention))
      if(!$item->lastSuspention->SuspentionReturned){
        if($item->lastSuspention->SuspentionWarrantyFix)
          return "Garantinis taisymas";
        if($item->lastSuspention->SuspentionUnwarrantedFix)
          return "Taisomas";
        if($item->lastSuspention->SuspentionUnconfirmedReturn)
          return "Laukia patvirtinimo";
      }
    if(!is_null($item->lastWithdrawal)){
      if($item->lastWithdrawal->ItemWithdrawalReturned == false){
        return "Naudojamas";
      }
    }
    if(!is_null($item->lastReservation))
      if(!$item->lastReservation->reservation->ReservationDelivered)
        return "Rezervuotas";

    return "Sandėlyje";
  }

  //checks item state
  public function checkItemState(Item $item){
    if(!$item->ItemConsumable){
      if($this->checkItemWithdrawal($item->ItemID)){
        if($this->checkItemSuspention($item->ItemID))
          $status = 'suspended';
        else $status = 'withdrew';
      }
      if($this->checkItemSuspention($item->ItemID))
          $status = 'suspended';
      if($this->checkItemReservation($item->ItemID))
        $status = "reserved";
      if($item->ItemDeleted)
        $status = "deleted";
    }
  }
  //checks if item is currently in reservation
  public function checkItemReservation($id){
    $reservationCheck = Item::find($id)->reservations()->get();
    foreach($reservationCheck as $res){
      if($res->reservation()->Active()->exists())
        return true;
    }
    return false;
  }

  //checks if item is currently in use by someone
  public function checkItemWithdrawal($id){
    if(Item::find($id)->withdrawals()->Active()->exists())
      return true;
    else {
      return false;
    }
  }
  //checks if item is currently suspended
  public function checkItemSuspention($id){
    if(Item::find($id)->suspentions()->Active()->exists())
      return true;
    return false;
  }
}
