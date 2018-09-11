<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentedItemRequest;
use Illuminate\Http\Request;

use App\RentedItem;
use App\CObject;
use Auth;

class RentedItemController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function create(RentedItemRequest $request){
      $rentedItem = RentedItem::create([
        'RentedItemName' => $request->name,
        'RentedItemNote' => $request->note,
        'RentedItemDailyPrice' => $request->price,
        'RentItemRent' => $request->rentDate
      ]);
      if($rentedItem)
        return response()->json(['message' => 'Atlikta', 'success' => 'Išnuomotas įrankis išsaugotas.']);
    }

    public function get($id = null){
      if($id == null || $id == ""){
        $items = RentedItem::Active()->with('cobject')->get();
      }
      else{
        $items = RentedItem::with('cobject')->find($id);
      }
      return response()->json($items, 200);
    }

    public function edit(RentedItemRequest $request){
      $item = RentedItem::find($request->id);
      try{
        if($item->RentedItemReturned)
          return response()->json(['message'=> 'Klaida', 'errors'=>['name'=>['Negalima redaguoti grąžinto įrankio informacijos.']]]);
        if($request->name && $request->name != "")
          $item->RentedItemName = $request->name;
        if($request->note)
          $item->RentedItemNote = $request->note;
        if($request->rentedDate)
          $item->RentedItemDate = $request->rentedDate;
        if($request->price)
          $item->RentedItemDailyPrice = $request->price;
        $item->save();

      }
      catch(Exception $e){
        return response()->json(['message' => 'Nežinoma klaida', 'errors'=> ['name'=> [$e->getMessage()]]], 422);
      }
      return response()->json(['message' => 'Atlikta', 'success'=> 'Nuomoto įrankio informacija redaguota.']);
    }

    public function assign(RentedItemRequest $request){
      $item = RentedItem::find($request->id);
      try{
        if($request->object){
          if($item->RentedItemReturned)
            return response()->json(['message'=> 'Klaida', 'errors'=>['name'=>['Negalima grąžinto įrankio priskirti objektui.']]]);

        $object = CObject::find($request->object);
        if($object->ObjectFinished)
          return response()->json(['message'=> 'Klaida', 'errors'=>['name'=>['Negalima priskirti įrankio uždarytam objektui.']]]);

        $item->ObjectID = $request->object;
        $item->save();
        }
        else{
          if($item->RentedItemReturned)
            return response()->json(['message'=> 'Klaida', 'errors'=>['name'=>['Negalima grąžinto įrankio priskirti objektui.']]]);

          $item->ObjectID = null;
          $item->save();
        }
      }
      catch(Exception $e){
        return response()->json(['message' => 'Nežinoma klaida', 'errors'=> ['name'=> [$e->getMessage()]]], 422);
      }
      return response()->json(['message' => 'Atlikta', 'success'=> 'Nuomotas įrankis priskirtas objektui.']);
    }

    public function return(RentedItemRequest $request){
      if(RentedItem::find($request->id)->update(['RentedItemReturned'=>true]))
        return response()->json(['message' => 'Atlikta', 'success'=> 'Nuomotas įrankis išsaugotas kaip atiduotas.']);
      else
        return response()->json(['message'=>'Klaida', 'errors'=>['name'=>['Įvyko klaida jungiantis į duombazę. Bandykite dar kartą.']]]);
    }
}
