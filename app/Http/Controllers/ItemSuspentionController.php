<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Suspentions\SuspentionRequest;
use Auth;
use App\ItemSuspention;
use App\ItemWithdrawal;
class ItemSuspentionController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    // creates item suspention as unconfirmed return
    public function unconfirmedReturn(SuspentionRequest $request){
      $withdrawal = ItemWithdrawal::where('ItemID', $request->id)->orderBy('created_at', 'DESC')->first();
      if($withdrawal){
        $suspention = ItemSuspention::create([
          'ItemID' => $request->id,
          'UserID' => $withdrawal->UserID,
          'SuspentionNote' => $request->note,
          'SuspentionUnconfirmedReturn' => true
        ]);
        if($suspention)
          return response()->json(['message'=> 'Atlikta', 'success' => 'Įrankis įšaldytas.'], 200);
      }
      else{
          return respone()->json(['message'=> 'Klaida','errors' => ['name' => ['Nepavyko rasti įrankio priskyrimo informacijos. Įvyko duomenų bazės klaida, arba įrankis nėra naudojamas.']]],422);
      }
    }

    // suspend item for warranted or unwarranted fix

    public function warrantedFix(SuspentionRequest $request){
      $suspention = ItemSuspention::create([
        'ItemID' => $request->id,
        'SuspentionNote' => $request->note,
        'SuspentionWarrantyFix' => true
      ]);
      if($suspention)
        return response()->json(['message'=> 'Atlikta', 'success' => 'Įrankis įšaldytas.'], 200);
      else{
        return response()->json(['message'=> 'Klaida','errors' => ['name' => ['Įvyko duomenų bazės klaida, arba įrankis nėra naudojamas.']]],422);
      }
    }

    public function unwarrantedFix(SuspentionRequest $request){

      if(ItemSuspention::create([
        'ItemID' => $request->id,
        'SuspentionNote' => $request->note,
        'SuspentionUnwarrantedFix' => true
      ]))
        return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis įšaldytas taisymui.'], 200);
      else
        return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
    }

    // marks item suspention as returned
    public function fixed(SuspentionRequest $request){
        $suspention = ItemSuspention::where('ItemID', $request->id)->orderBy('created_at', 'DESC')->first();
        if($suspention){
            $suspention->SuspentionReturned = true;
            $suspention->save();
            return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis grąžintas į sandėlį.'], 200);
        }
        else
          return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
    }

    // marks item suspention of unconfirmed return as returned and completes item withdrawal return
    public function returnConfirmed(SuspentionRequest $request){
      if(Auth::user()->UserRFIDCode != $request->code)
        return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Nuskaityta kortelė priklauso ne jums. Nuskaitykite savo kortelę.']]], 422);
      $suspention = ItemSuspention::where('ItemID', $request->id)->orderBy('created_at', 'DESC')->first();
      if($suspention)
        {
          $suspention->SuspentionReturned = true;
          $suspention->save();
        }
      $withdrawal = ItemWithdrawal::where('ItemID', $request->id)->orderBy('created_at', 'DESC')->first();
      if($withdrawal){
        $withdrawal->ItemWithdrawalReturned = true;
        $withdrawal->ItemWithdrawalReturnedQuantity = $withdrawal->ItemWithdrawalQuantity;
        $withdrawal->ItemWithdrawalReturnConfirmCard = $request->code;
        $withdrawal->ItemWithdrawalReturnConfirmedBy = Auth::user()->UserID;
        $withdrawal->save();
      }
      return response()->json(['message' => 'Atlikta', 'success' => 'Įrankio įšaldymas panaikintas, įrankis grąžintas į sandėlį.'], 200);
    }

    public function getWaitingConfirmationSuspentions(){
        $suspentions = ItemSuspention::Active()->where('SuspentionUnconfirmedReturn', true)->with(['item' => function($q){ return $q->with('itemGroup');}])->orderBy("created_at", "ASC")->get();
        return response()->json($suspentions, 200);
    }

    public function getFixSuspentions(){
        $suspentions = ItemSuspention::Active()->where(function ($q){
            return $q->where('SuspentionWarrantyFix', true)->orWhere('SuspentionUnwarrantedFix', true);
        })->with(['item' => function($q){ return $q->with('itemGroup');}])->orderBy("created_at", "ASC")->get();
        return response()->json($suspentions, 200);
    }

}
