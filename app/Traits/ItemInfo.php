<?php
namespace App\Traits;
use App\Item;
use App\ItemGroup;
trait ItemInfo{

  public function GetItemState(Item $item){

    if($item->ItemDeleted){
      return "Ištrintas";
    }
    if(!is_null($item->lastWithdrawal)){
      if($item->lastWithdrawal->ItemWithdrawalReturned == false){
        return "Naudojamas";
      }
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
    if(!is_null($item->lastReservation))
      if(!$item->lastReservation->reservation->ReservationDelivered)
        return "Rezervuotas";

    return "Sandėlyje";
  }
}
