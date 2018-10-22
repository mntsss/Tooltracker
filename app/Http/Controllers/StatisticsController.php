<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Request\Suspentions\MonthlyFixesRequest;
use App\ItemSuspention;
use App\Item;
use App\RentedItem;
use App\Traits\ItemInfo;
class StatisticsController extends Controller
{

  use ItemInfo;

  public function calculateMonthFixes(){
    // if(!isset($request->date)){
    //
    // }
    $date = date("Y-m-01");
    $fixesCount = ItemSuspention::where(function($q){
      $q->where('SuspentionWarrantyFix', true)->orWhere('SuspentionUnwarrantedFix', true);
    })->where('created_at', '>=', $date)->count();

    return response()->json($fixesCount, 200);
  }

  public function countItems(){
    $count = Item::existing()->count();
    return response()->json($count, 200);
  }

  public function countItemsInUse(){
    $items = Item::existing()->with(['lastWithdrawal' => function($query){ $query->with(['user', 'object']);}, 'lastSuspention' => function($query){ $query->with(['user']);}, 'lastReservation', 'images'])->get();
    $count = 0;
    foreach($items as $item){
      //array_push($response, ['item'=> $item, 'state' => $this->GetItemState($item)]);
      if($this->GetItemState($item) == "Naudojamas")
        $count++;
    }
    return response()->json($count, 200);
  }

  public function calculateMonthRentPrice(){
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
      var_dump($rentPeriod);
      if($rentPeriod < 0) $rentPeriod = 0;
      $cost += $rentPeriod * $item->RentedItemDailyPrice;
    }
    return response()->json(round($cost,2), 200);
  }
   public function daysDifference($start, $end){

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
