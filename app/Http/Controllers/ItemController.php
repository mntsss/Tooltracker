<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemGroup;
use Auth;

use App\Traits\ItemInfo;

class ItemController extends Controller
{
  use ItemInfo;

    public function __construct(){
      //$this->middleware('auth');
    }

    public function items($groupID){
      $items = Item::where('ItemGroupID', $groupID)->with('lastWithdrawal', 'lastSuspention', 'lastReservation')->get();
      $response = [];
      foreach($items as $item){
        array_push($response, ['item'=> $item, 'state' => $this->GetItemState($item)]);
      }
      return response()->json($response);
    }

    public function addItem(Request $request){

    }
}
