<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WithdrawalReturnRequest;
use Auth;
use App\ItemWithdrawal;
use App\Item;
class ItemWithdrawalController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }

  public function return(WithdrawalReturnRequest $request){
    if($request->code != Auth::user()->UserRFIDCode){
      return response()->json(['message'=>'Klaida!', 'error'=>['name'=>['Įrankio grąžinimas turi būti patvirtintas Jūsų (administratoriaus) kortele!']]]);
    }
    $withdrawal = ItemWithdrawal::find($request->id);
    if($withdrawal){
      $withdrawal->ItemWithdrawalReturnConfirmCard = $request->code;
      $withdrawal->ItemWithdrawalReturnConfirmedBy = Auth::user()->UserID;
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
}
