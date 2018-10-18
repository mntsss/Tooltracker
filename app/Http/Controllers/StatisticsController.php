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
    $date = date("01-m-Y");
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
      if($this->GetItemState($item) === "Naudojamas")
        $count++;
    }
    return response()->json($count, 200);
  }

  public function calculateMonthRentPrice(){
    $date = date("01-m-Y");
    $cost = 0;
    $items = RentedItem::where('RentedItemDate', '>=', $date)->get();
    foreach($items as $item){
      if($item->RentedItemReturned){
        $rentEndTime = strtotime($item->updated_at);
      }
      else{
        $rentEndTime = time();
      }
      $rentPeriod = $rentEndTime- strtotime($item->RentedItemDate);
      if($rentPeriod < 0) $rentPeriod = 0;
      $rentDays = round($rentPeriod / (60 * 60 * 24));
      var_dump($rentDays);
      $cost += $rentDays * $item->RentedItemDailyPrice;
    }
    return response()->json(round($cost,2), 200);
  }
}
