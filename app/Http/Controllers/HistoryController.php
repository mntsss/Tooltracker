<?php

namespace App\Http\Controllers;

use App\Action;
use Illuminate\Http\Request;
use App\Item;
use App\Traits\ActionHistory;
use App\Http\Services\ValidationService;

class HistoryController extends Controller
{
  use ActionHistory;

  private $validationService;

  public function __construct(ValidationService $validationService)
  {
    $this->validationService = $validationService;
  }

    public function getItemsHistory($from = null, $til = null){
      $query = Action::with(['item', 'user', 'storage'])->orderBy('created_at', 'DESC');
      if($from){
          $query->where('created_at', '>=', $from);
      }
      if($til){
          $query->where('created_at', '<=', $til);
      }
      $actions = $query->get();

      return response()->json($actions, 200);
    }

    public function getUserHistory($userID, $from = null, $til = null){
      $items = Item::with(['withdrawals' => function($q) use($userID){ $q->where('UserID', $userID);}, 'suspentions' => function($q)use($userID){$q->where('UserID', $userID);}])->get();
      $collection = collect();
      foreach($items as $item){
        $withdrawals = $this->createWithdrawalHistory($item, $from, $til);
        $suspentions = $this->createSuspentionHistory($item, $from, $til);
        $collection = $collection->merge($withdrawals)
                                 ->merge($suspentions)
                                 ;
      }


      $sorted = $collection->sortByDesc('Date')->values()->all();

      return response()->json($sorted, 200);
    }
}
