<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\RenameItemRequest;
use App\Http\Requests\AddItemChipRequest;
use App\Http\Requests\FindItemWithCodeRequest;
use App\Item;
use App\ItemGroup;
use App\RfidCode;
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
      $items = Item::where('ItemGroupID', $groupID)->existing()->with('lastWithdrawal', 'lastSuspention', 'lastReservation')->get();
      $response = [];
      foreach($items as $item){
        array_push($response, ['item'=> $item, 'state' => $this->GetItemState($item)]);
      }
      return response()->json($response);
    }

    public function create(CreateItemRequest $request){
      if(!is_null($request->image) && $request->image != "null" && $request->image != ""){
        Validator::make($request->all(), ['image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:40496'],
        [
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
      if($request->code == '')$request->code = null;
      if($request->nocode) $request->code = null;
      $item = Item::create([
        'ItemName' => $request->name,
        'ItemConsumable' => $request->consumable,
        'ItemImage' => $input['imagename'],
        'ItemWarranty' => $request->warranty_date,
        'ItemPurchase' => $request->purchase_date,
        'ItemGroupID' => $request->groupID
      ]);
      if($item){
        if($request->code)
        {
          if(RfidCode::create(['Code' => $request->code, 'ItemID' => $item->ItemID])){

          }
          else
          {
            return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
          }
        }
        else
        {
          return response()->json(['message' => 'Atlikta!', 'success'=>'Įrankis pridėtas į grupę.'], 200);
        }
      }
      else return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
    }

    public function get($id){

        $item = Item::where('ItemID', $id)->existing()->with('lastWithdrawal', 'lastSuspention', 'lastReservation')->first();
          return response()->json(['item'=> $item, 'state' => $this->GetItemState($item)], 200);
    }

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

    public function delete($id){
      $reservationCheck = Item::find($id)->reservations()->get();
      foreach($reservationCheck as $res){
        if($res->reservation()->Active()->exists())
          return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankis priskirtas aktyviai rezercavijai, todėl negali būti ištrintas.']]],422);
      }
      if(Item::find($id)->withdrawals()->Active()->exists())
        return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankis priskirtas vartotojui, todėl negali būti ištrintas.']]],422);
      else if(Item::find($id)->update(['ItemDeleted' => true]))
        return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis sėkmingai ištrintas.'], 200);
    else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]],422);
    }

    public function addchip(AddItemChipRequest $request){
      if(RfidCode::create(['Code' => $request->code, 'ItemID' => $request->id]))
        return response()->json(['message' => 'Atlikta', 'success' => 'Naujas čipas buvo sėkmingai priskirtas įrankiui!'],200);
      else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]],422);
    }

    public function findWithCode(FindItemWithCodeRequest $request){
      return response()->json(RfidCode::where('Code', $request->code)->first()->item);
    }
}
