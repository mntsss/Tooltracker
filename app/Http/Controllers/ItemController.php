<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateItemRequest;
use App\Item;
use App\ItemGroup;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ItemInfo;

class ItemController extends Controller
{
  use ItemInfo;

    public function __construct(){
      //$this->middleware('auth');
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
        $destinationPath = public_path('/media/uploads/');
        $image->move($destinationPath, $input['imagename']);
      }else {
        $input['imagename'] = "";
      }

      if(Item::create([
        'ItemName' => $request->name,
        'ItemCode' => $request->code,
        'ItemConsumable' => $request->consumable,
        'ItemImage' => $input['imagename'],
        'ItemWarranty' => $request->warranty_date,
        'ItemPurchase' => $request->purchase_date,
        'ItemGroupID' => $request->groupID
      ]))
        return response()->json(['message' => 'Atlikta!', 'success'=>'Įrankis pridėtas į grupę.'], 200);
      else return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);

    }
}
