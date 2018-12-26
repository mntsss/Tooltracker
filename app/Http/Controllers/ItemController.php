<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\AddItemChipRequest;
use App\Http\Requests\FindItemWithCodeRequest;
use App\Http\Requests\ItemSearchRequest;
use App\Item;
use App\ItemGroup;
use App\RfidCode;
use App\ItemWithdrawal;
use App\ItemImage;
use App\ItemSuspention;
use App\Action;
use Auth;

use App\Traits\ActionHistory;
use App\Traits\ItemInfo;

class ItemController extends Controller
{
  use ItemInfo;
  use ActionHistory;

    public function __construct(){
       $this->middleware('auth');
   }

   // returns item list for provided group id
    public function items($groupID){
      $items = Item::where('ItemGroupID', $groupID)->existing()->with(['lastWithdrawal' => function($query){ $query->with(['user', 'object']);}, 'lastSuspention' => function($query){ $query->with(['user']);}, 'lastReservation', 'images'])->get();
      foreach($items as $item){
        $item->state = $this->GetItemState($item);
      }
      return response()->json($items);
    }

    // returns suspended item list
    public function suspendedItems(){
        $items = Item::existing()->with(['lastWithdrawal' => function($query){ $query->with(['user', 'object']);}, 'lastSuspention' => function($query){ $query->Active()->with(['user']);}, 'lastReservation', 'images'])->get();
        $response = [];
        foreach($items as $item){
            if($item->lastSuspention)
                array_push($response, ['item'=> $item, 'state' => $this->GetItemState($item)]);
        }
        return response()->json($response);
    }

    public function deletedItems(){
        $items = Item::where('ItemDeleted', true)->with(['lastWithdrawal', 'lastSuspention', 'lastReservation', 'images'])->orderBy('updated_at', 'DESC')->get();
        return response()->json($items, 200);
    }

    public function history($id){
        $item = Item::with(['withdrawals', 'reservations'])->find($id);
        $withdrawals = $this->createWithdrawalHistory($item);
        $reservations = $this->createReservationHistory($item);
        $suspentions = $this->createSuspentionHistory($item);

        $sorted = $withdrawals->merge($reservations)->merge($suspentions)->sortByDesc('Date')->values()->all();

        return response()->json(['actions' => $sorted, 'item' => $item], 200);
    }

    public function create(ItemRequest $request){
      if($request->nocode) $request->code = null;
      $item = Item::create([
        'ItemName' => $request->name,
        'ItemConsumable' => $request->consumable,
        'ItemWarranty' => $request->warranty_date,
        'ItemPurchase' => $request->purchase_date,
        'ItemGroupID' => $request->groupID,
        'ItemIdNumber' => $request->idnumber,
        'ItemAcquiredFrom' => $request->acquired
      ]);
      if($item){
        if($request->code)
        {
          if(!RfidCode::create(['Code' => $request->code, 'ItemID' => $item->ItemID]))
          {
            return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
          }
        }
      }
      else return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);

      //image upload
      if($request->image != null){
        //get image name
        $explExtension = explode('.', $request->image['name']);
        $extension = end($explExtension);
        $imageName = $explExtension[0].'_'.time().'.'.$extension;
        //get image code
        $imageExpl = explode(',',$request->image['dataUrl']);
        $base64 = end($imageExpl);
        //saving image as a file

        if(file_put_contents(public_path(env('IMAGE_UPLOAD_ROUTE')).$imageName, base64_decode($base64))){
            if(!ItemImage::create([
                'ImageName' => $imageName,
                'ItemID' => $item->ItemID
            ])){
                return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
            }
        }
        else {
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida išsaugant nuotrauką failų sistemoje. Susisiekite su administratoriumi.']]], 422);
        }
      }
      return response()->json(['message' => 'Atlikta!', 'success'=>'Įrankis pridėtas į grupę.'], 200);
    }

    public function edit(ItemRequest $request){
      $item = Item::find($request->id);

      if($item){
        if($request->has('name'))
          $item->ItemName = $request->name;
        if($request->has('warranty_date'))
          $item->ItemWarranty = $request->warranty_date;
        if($request->has('purchase_date'))
          $item->ItemPurchase = $request->purchase_date;
        if($request->has('note'))
          $item->ItemNote = $request->note;
        if($request->has('idnumber'))
          $item->ItemIdNumber = $request->idnumber;
        if($request->has('acquired'))
          $item->ItemAcquiredFrom = $request->acquired;

        $item->save();
        return response()->json(['message' => 'Atlikta', 'success' => 'Įrankio informacija sėkmingai redaguota.']);
      }else {
        return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įrankio nepavyko identifikuoti, arba įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
      }
    }
    // returns item and its state by provided ID
    public function get($id){

        $item = Item::where('ItemID', $id)->with(['lastWithdrawal' => function($query){ $query->with(['user', 'object']);}, 'lastSuspention' => function($query){ $query->with(['user']); }, 'lastReservation', 'images'])->first();
        $item->state = $this->GetItemState($item);
          return response()->json($item, 200);
    }
    // restore item marked as deleted to selected item group

    public function restore(ItemRequest $request){
        if(Item::find($request->id)->update(['ItemDeleted' => false, 'ItemGroupID' => $request->groupID]))
            return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis sėkmingai atkurtas į pasirinktą grupę.'], 200);
        else
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
    }

    // marks item as deleted
    public function delete(ItemRequest $request){

      $id = $request->id;
      if($this->checkItemReservation($id))
          return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankis priskirtas aktyviai rezercavijai, todėl negali būti ištrintas.']]],422);

      if($this->checkItemWithdrawal($id))
        return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankis priskirtas vartotojui, todėl negali būti ištrintas.']]],422);

      if($this->checkItemSuspention($id))
        return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankis suspenduotas (įšaldytas), todėl negali būti ištrintas.']]],422);

      if(Item::find($id)->update(['ItemDeleted' => true]))
        return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis sėkmingai ištrintas.'], 200);
    else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]],422);
    }

    // add new RFID chip for item
    public function addchip(AddItemChipRequest $request){
      if(RfidCode::create(['Code' => $request->code, 'ItemID' => $request->id]))
        return response()->json(['message' => 'Atlikta', 'success' => 'Naujas čipas buvo sėkmingai priskirtas įrankiui!'],200);
      else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]],422);
    }
    // finds item which is assigned to provided RFID code
    public function findWithCode(FindItemWithCodeRequest $request){
        $itemID = RfidCode::where('Code', $request->code)->first()->ItemID;
        $item = Item::existing()->with(['lastWithdrawal', 'lastSuspention', 'lastReservation', 'images', 'itemGroup'])->find($itemID);
        $item->state = $this->getItemState($item);
      return response()->json($item, 200);
    }

    public function search(ItemSearchRequest $request){
      $items = Item::where('ItemName', 'like','%'.$request['query'].'%')->orWhere('ItemIdNumber', 'like', $request['query'].'%')->existing()->with(['lastWithdrawal', 'lastSuspention', 'lastReservation', 'images', 'itemGroup'])->limit(10)->get();

      foreach($items as $item){
        $item->state = $this->GetItemState($item);
      }
      return response()->json($items, 200);
    }

}
