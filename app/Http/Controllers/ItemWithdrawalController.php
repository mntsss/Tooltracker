<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WithdrawalReturnRequest;
use App\Http\Requests\Withdrawal\ReturnConsumableRequest;
use Auth;
use App\ItemWithdrawal;
use App\Item;
class ItemWithdrawalController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }

  public function return(WithdrawalReturnRequest $request){
    if($request->code != Auth::user()->code){
      return response()->json(['message'=>'Klaida!', 'error'=>['name'=>['Įrankio grąžinimas turi būti patvirtintas Jūsų (administratoriaus) kortele!']]]);
    }
    $withdrawal = ItemWithdrawal::find($request->id);
    if($withdrawal){
      $withdrawal->ItemWithdrawalReturnConfirmCard = $request->code;
      $withdrawal->ItemWithdrawalReturnConfirmedBy = Auth::user()->id;
      $withdrawal->ItemWithdrawalReturned = true;
      $withdrawal->ItemWithdrawalReturnedQuantity = $request->quantity;
      $withdrawal->save();
      return response()->json(['message'=> 'Atlikta!', 'success' => 'Įrankis sėkmingai grąžintas į sandėlį.'], 200);
    }else{
      return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]],422);
    }
  }
  public function get($id){
      $item = Item::with(['lastWithdrawal' => function($query){
          $query->with('image');
      }])->find($id);
      return response()->json($item, 200);
  }

  public function returnConsumable(ReturnConsumableRequest $request){
      $withdrawals = ItemWithdrawal::where('ObjectID', $request->objectID)->where('ItemID', $request->id)->Active()->get();
      $quantity = $request->quantity;

      $w_ID = 0;
      while($quantity > 0 && $w_ID < count($withdrawals)){
        if($withdrawals[$w_ID]->ItemWithdrawalQuantity >= $quantity){
          $withdrawals[$w_ID]->ItemWithdrawalReturnedQuantity = $quantity;
          $withdrawals[$w_ID]->ItemWithdrawalReturnConfirmedBy = Auth::user()->id;
          $withdrawals[$w_ID]->ItemWithdrawalReturned = true;
          $withdrawals[$w_ID]->save();
          break;
        }else{
          $withdrawals[$w_ID]->ItemWithdrawalReturnedQuantity = $withdrawals[$w_ID]->ItemWithdrawalQuantity;
          $withdrawals[$w_ID]->ItemWithdrawalReturnConfirmedBy = Auth::user()->id;
          $withdrawals[$w_ID]->ItemWithdrawalReturned = true;
          $withdrawals[$w_ID]->save();
          $quantity -=  $withdrawals[$w_ID]->ItemWithdrawalQuantity;
        }
      }
      foreach($withdrawals as $w){
        if(!$w->ItemWithdrawalReturned){
          $w->ItemWithdrawalReturnedQuantity = 0;
          $w->ItemWithdrawalReturnConfirmedBy = Auth::user()->id;
          $w->ItemWithdrawalReturned = true;
          $w->save();
        }
      }

      return response()->json(['message'=> 'Atlikta!', 'success' => 'Įrankiai sėkmingai grąžinti į sandėlį.'], 200);
  }

  public function writeOff($id)
  {
    $withdrawal = ItemWithdrawal::find($id);
    $withdrawal->ItemWithdrawalReturnedQuantity = 0;
    $withdrawal->ItemWithdrawalReturnConfirmedBy = Auth::user()->id;
    $withdrawal->ItemWithdrawalReturned = true;
    $withdrawal->save();

    return response()->json(['message'=> 'Atlikta!', 'success' => 'Sėkmingai nurašyta.'], 200);
  }
}
