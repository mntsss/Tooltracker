<?php
namespace App\Traits;

use Illuminate\Support\Collection;

use App\Item;
use App\ItemWithdrawal;
use App\ItemSuspention;
use App\ReservationItem;
use App\Action;

trait ActionHistory{

    public function createWithdrawalHistory(Item $item, $from = null, $til = null){
        $actionArray = [];
        foreach($item->withdrawals as $withdrawal){
            $action = new Action();
            $action->ItemName = $item->ItemName;
            if($item->itemGroup)
              $action->GroupName = $item->itemGroup->ItemGroupName;
            $action->ItemID = $item->ItemID;
            $action->Action = "withdrawal";
            $action->Type = "out";
            $action->Username = $withdrawal->user->Username;
            $action->UserID = $withdrawal->UserID;
            $action->Date = $withdrawal->created_at;
            $action->Quantity = $withdrawal->ItemWithdrawalQuantity;
            $action->ActionID = $withdrawal->ItemWithdrawalID;
            if($from || $til){
              if((new \DateTime($from)) <= (new \DateTime($action->Date)) && (new \DateTime($til)) >= (new \DateTime($action->Date)))
                $actionArray[] = $action->toArray();
            }
            else{
              $actionArray[] = $action->toArray();
            }
            if($withdrawal->ItemWithdrawalReturned){
                $action = new Action();
                $action->ItemName = $item->ItemName;
                if($item->itemGroup)
                  $action->GroupName = $item->itemGroup->ItemGroupName;
                $action->ItemID = $item->ItemID;
                $action->Action = "withdrawal";
                $action->Type = "in";
                $action->Username = $withdrawal->user->Username;
                $action->UserID = $withdrawal->UserID;
                $action->Date = $withdrawal->updated_at;
                $action->Quantity = $withdrawal->ItemWithdrawalReturnedQuantity;
                $action->ActionID = $withdrawal->ItemWithdrawalID;
                if($from || $til){
                  if((new \DateTime($from)) <= (new \DateTime($action->Date)) && (new \DateTime($til)) >= (new \DateTime($action->Date)))
                    $actionArray[] = $action->toArray();
                }
                else{
                  $actionArray[] = $action->toArray();
                }
            }
        }
        $withdrawalActions = collect($actionArray);
        return $withdrawalActions;
    }

    public function createReservationHistory(Item $item, $from = null, $til = null){
        $actionArray = [];
        foreach($item->reservations as $reservation){
            $action = new Action();
            $action->ItemName = $item->ItemName;
            if($item->itemGroup)
              $action->GroupName = $item->itemGroup->ItemGroupName;
            $action->ItemID = $item->ItemID;
            $action->Action = "reservation";
            $action->Type = "out";
            $action->Username = $reservation->reservation->user->Username;
            $action->UserID = $reservation->reservation->UserID;
            $action->Date = $reservation->created_at;
            $action->Quantity = $reservation->ReservationItemQuantity;
            $action->ActionID = $reservation->ReservationItemID;
            if($from || $til){
              if((new \DateTime($from)) <= (new \DateTime($action->Date)) && (new \DateTime($til)) >= (new \DateTime($action->Date)))
                $actionArray[] = $action->toArray();
            }
            else{
              $actionArray[] = $action->toArray();
            }
            if($reservation->reservation->ReservationDelivered){
                $action = new Action();
                $action->ItemName = $item->ItemName;
                if($item->itemGroup)
                  $action->GroupName = $item->itemGroup->ItemGroupName;
                $action->ItemID = $item->ItemID;
                $action->Action = "reservation";
                $action->Type = "in";
                $action->Username = $reservation->reservation->user->Username;
                $action->UserID = $reservation->reservation->UserID;
                $action->Date = $reservation->reservation->updated_at;
                $action->Quantity = $reservation->ReservationItemQuantity;
                $action->ActionID = $reservation->ReservationItemID;
                if($from || $til){
                  if((new \DateTime($from)) <= (new \DateTime($action->Date)) && (new \DateTime($til)) >= (new \DateTime($action->Date)))
                    $actionArray[] = $action->toArray();
                }
                else{
                  $actionArray[] = $action->toArray();
                }
            }
        }
        $reservationActions = collect($actionArray);
        return $reservationActions;
    }

    public function createSuspentionHistory(Item $item, $from = null, $til = null){
        $actionArray = [];
        foreach($item->suspentions as $suspention){
            $action = new Action();
            if($item->itemGroup)
              $action->GroupName = $item->itemGroup->ItemGroupName;
            $action->ItemName = $item->ItemName;
            $action->ItemID = $item->ItemID;
            $action->Action = "suspention";
            $action->Type = "out";
            if($suspention->UserID)
            {
                $action->Username = $suspention->user->Username;
                $action->UserID = $suspention->UserID;
            }
            $action->Date = $suspention->created_at;
            $action->ActionID = $suspention->SuspentionID;
            if($suspention->SuspentionWarrantyFix)
                $action->Subtype = "warranty_fix";
            if($suspention->SuspentionUnwarrantedFix)
                $action->Subtype = "unwarranted_fix";
            if($suspention->SuspentionUnconfirmedReturn)
                $action->Subtype = "unconfirmed_return";
            if($from || $til){
              if((new \DateTime($from)) <= (new \DateTime($action->Date)) && (new \DateTime($til)) >= (new \DateTime($action->Date)))
                $actionArray[] = $action->toArray();
            }
            else{
              $actionArray[] = $action->toArray();
            }
            if($suspention->SuspentionReturned){
                $action = new Action();
                $action->ItemName = $item->ItemName;
                if($item->itemGroup)
                  $action->GroupName = $item->itemGroup->ItemGroupName;
                $action->ItemID = $item->ItemID;
                $action->Action = "suspention";
                $action->Type = "in";
                if($suspention->UserID)
                {
                    $action->Username = $suspention->user->Username;
                    $action->UserID = $suspention->UserID;
                }
                $action->Date = $suspention->updated_at;
                $action->ActionID = $suspention->SuspentionID;
                if($suspention->SuspentionWarrantyFix)
                    $action->Subtype = "warranty_fix";
                if($suspention->SuspentionUnwarrantedFix)
                    $action->Subtype = "unwarranted_fix";
                if($suspention->SuspentionUnconfirmedReturn)
                    $action->Subtype = "unconfirmed_return";
                if($from || $til){
                  if((new \DateTime($from)) <= (new \DateTime($action->Date)) && (new \DateTime($til)) >= (new \DateTime($action->Date)))
                    $actionArray[] = $action->toArray();
                }
                else{
                  $actionArray[] = $action->toArray();
                }
            }
        }
        $suspentionsActions = collect($actionArray);
        return $suspentionsActions;
    }

}
