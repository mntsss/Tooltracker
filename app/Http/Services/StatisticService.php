<?php

namespace App\Http\Services;

use App\Item;
use App\Action;
use App\RentedItem;

class StatisticService
{
  public function get(){
    return [
      'items_total' => $this->itemsTotal(),
      'items_in_use' => $this->itemsInUse(),
      'monthly_fixes' => $this->monthlyFixes(),
      'monthly_rent_price' => $this->monthlyRentCost()
    ];
  }

  private function monthlyFixes():int
  {
    return Action::where(function($q){
      $q->where('current_status', Item::ITEM_WARRANTED_FIX)->orWhere('current_status', Item::ITEM_UNWARRANTED_FIX);
    })->where('created_at', '>=', date("Y-m-01"))->count();
  }

  private function monthlyRentCost()
  {
    $date = date("Y-m-01");
    $cost = 0;
    $items = RentedItem::where('created_at', '>=', date("Y-01-01"))->get();
    foreach($items as $item){
      if($item->RentedItemDate < $date){
        $startDate = strtotime($date);
      }
      else {
        $startDate = strtotime($item->RentedItemDate);
      }
      if($item->RentedItemReturned){
        $rentEndTime = strtotime($item->updated_at);
      }
      else{
        $rentEndTime = time();
      }
      $rentPeriod = $this->daysDifference($startDate, $rentEndTime);
      if($rentPeriod < 0) $rentPeriod = 0;
      $cost += $rentPeriod * $item->RentedItemDailyPrice;
    }
    return $cost;
  }

  private function itemsTotal():int
  {
    return Item::existing()->count();
  }

  private function itemsInUse():int
  {
    return Item::existing()->where('status', Item::ITEM_IN_USE)->count();
  }

  private function daysDifference($start, $end){

     // otherwise the  end date is excluded (bug?)
     //var_dump([$start,$end]);
     $start = new \DateTime(date("Y-m-d", $start));
     $end = new \DateTime(date("Y-m-d", $end));
     $end->modify('+1 day');

     $interval = $end->diff($start);
     // total days
     $days = $interval->days;

     // create an iterateable period of date (P1D equates to 1 day)
     $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

     // best stored as array, so you can add more than one
     $holidays = array('2018-11-01', '2018-12-25', '2018-12-26', '2019-01-01');

     foreach($period as $dt) {
         $curr = $dt->format('D');

         // substract if Saturday or Sunday
         if ($curr == 'Sat' || $curr == 'Sun') {
             $days--;
         }

         // (optional) for the updated question
         elseif (in_array($dt->format('Y-m-d'), $holidays)) {
             $days--;
         }
     }
     return $days;
  }
}
