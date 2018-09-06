<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\RenameItemRequest;
use App\Http\Requests\AddItemChipRequest;
use App\Http\Requests\FindItemWithCodeRequest;
use App\Http\Requests\ItemSearchRequest;
use App\Http\Requests\GetItemWithdrawalInfo;
use App\Http\Requests\ReturnItemWithCardRequest;
use App\Http\Requests\SuspendItemUnconfirmedRequest;
use App\Item;
use App\ItemGroup;
use App\RfidCode;
use App\ItemWithdrawal;
use App\ItemImage;
use App\ItemSuspention;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ItemInfo;

class ItemController extends Controller
{
  use ItemInfo;

    public function __construct(){
       $this->middleware('auth');
   }

    public function items($groupID){
      $items = Item::where('ItemGroupID', $groupID)->existing()->with(['lastWithdrawal', 'lastSuspention', 'lastReservation', 'images'])->get();
      $response = [];
      foreach($items as $item){
        array_push($response, ['item'=> $item, 'state' => $this->GetItemState($item)]);
      }
      return response()->json($response);
    }

    public function create(CreateItemRequest $request){

      if($request->nocode) $request->code = null;
      $item = Item::create([
        'ItemName' => $request->name,
        'ItemConsumable' => $request->consumable,
        'ItemWarranty' => $request->warranty_date,
        'ItemPurchase' => $request->purchase_date,
        'ItemGroupID' => $request->groupID,
        'ItemIdNumber' => $request->idnumber
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

        if(file_put_contents(public_path(env('ITEMS_IMAGE_ROUTE')).$imageName, base64_decode($base64))){
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
    // returns item and its state by provided ID
    public function get($id){

        $item = Item::where('ItemID', $id)->existing()->with('lastWithdrawal', 'lastSuspention', 'lastReservation')->first();
          return response()->json(['item'=> $item, 'state' => $this->GetItemState($item)], 200);
    }
    // renames item
    public function rename(RenameItemRequest $request){

        if(Item::find($request->id)->update(['ItemName' => $request->name]))
            return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis sėkmingai pervadintas.'], 200);
        else
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
    }

    public function changeImage(Request $request){

        Validator::make($request->all(), [ 'id' => 'required|numeric'], [
          'id.required' => 'Įrankis nežinomas. Apie klaidą praneškina administratoriui.',
          'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.'
        ])->validate();

        if(!is_null($request->image) && $request->image != "null" && $request->image != ""){
          Validator::make($request->all(), ['image' =>'image|mimes:jpg,jpeg,gif,png|max:4096'], [
            'image.image' => 'Galite įkelti tik nuotrauką ar paveikslėlį!',
            'image.mimes' => 'Netinkamas failo plėtinys',
            'image.max' => 'Failas per didelis.'
            ])->validate();
          $image = $request->file('image');
          $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
          $destinationPath = public_path('/media/items/');
          $image->move($destinationPath, $input['imagename']);
        }else {
          $input['imagename'] = "";
        }

        if(Item::find($request->id)->update(['ItemImage' => $input['imagename']]))
            return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankio nuotrauka sėkmingai pakeista.'], 200);
        else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]],422);
    }

    // marks item as deleted
    public function delete($id){

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
        $item = RfidCode::where('Code', $request->code)->first()->item;
        $status = null;
        if($this->checkItemSuspention($item->ItemID))

        if($this->checkItemWithdrawal($item->ItemID)){
          if($this->checkItemSuspention($item->ItemID))
            $status = 'suspended';
          else $status = 'withdrew';
        }
        if($this->checkItemReservation($item->ItemID))
          $status = "reserved";
        if($item->ItemDeleted)
          $status = "deleted";
      return response()->json(['item' => $item, 'status' => $status], 200);
    }

    // returns item's withdrawal info for return confirmation
    public function itemWithdrawalInfo(GetItemWithdrawalInfo $request){
        $item = Item::with(['lastWithdrawal' => function($query){
            $query->with('image');
        }])->find($request->id);
        return response()->json($item, 200);
    }

    public function search(ItemSearchRequest $request){
      $items = Item::where('ItemName', 'like','%'.$request['query'].'%')->existing()->with(['lastWithdrawal', 'lastSuspention', 'lastReservation', 'images'])->limit(10)->get();
      $response = [];
      foreach($items as $item){
        array_push($response, ['item'=> $item, 'state' => $this->GetItemState($item)]);
      }
      return response()->json($response, 200);
    }

    // marks item's withdrawal as returned
    public function returnItemWithCard(ReturnItemWithCardRequest $request){
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

    // creates item suspention as unconfirmed return
    public function suspendUnconfirmedReturn(SuspendItemUnconfirmedRequest $request){
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

    //checks if item is currently in reservation
    public function checkItemReservation($id){
      $reservationCheck = Item::find($id)->reservations()->get();
      foreach($reservationCheck as $res){
        if($res->reservation()->Active()->exists())
          return true;
      }
      return false;
    }

    //checks if item is currently in use by someone
    public function checkItemWithdrawal($id){
      if(Item::find($id)->withdrawals()->Active()->exists())
        return true;
      else {
        return false;
      }
    }
    //checks if item is currently suspended
    public function checkItemSuspention($id){
      if(Item::find($id)->suspentions()->Active()->exists())
        return true;
      return false;
    }
}
