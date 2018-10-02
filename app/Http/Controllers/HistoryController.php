<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Traits\ActionHistory;

class HistoryController extends Controller
{
  use ActionHistory;

    public function getItemsHistory(){
      $items = Item::with(['withdrawals', 'reservations'])->get();
      $collection = collect();
      foreach($items as $item){
        $withdrawals = $this->createWithdrawalHistory($item);
        $reservations = $this->createReservationHistory($item);
        $suspentions = $this->createSuspentionHistory($item);
        $collection = $collection->merge($withdrawals)
                                 ->merge($reservations)
                                 ->merge($suspentions)
                                 ;
      }


      $sorted = $collection->sortByDesc('Date')->values()->all();

      return response()->json($sorted, 200);
    }
}
