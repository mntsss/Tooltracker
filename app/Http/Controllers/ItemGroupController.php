<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateItemGroupRequest;
use App\Http\Requests\RenameItemGroupRequest;
use App\Http\Requests\GroupChangeImageRequest;

use Illuminate\Support\Facades\Validator;
use App\ItemGroup;
use Auth;
use App\Item;
use App\Traits\ItemInfo;

class ItemGroupController extends Controller
{
    use ItemInfo;

    public function __construct(){
        $this->middleware('auth');
    }
    public function create(CreateItemGroupRequest $request){

      if($request->image == null)
        $image = null;
      else{
        $image = $request->image['dataUrl'];
      }

      ItemGroup::create([
        'ItemGroupName' => $request->name,
        'ItemGroupImage' => $image
      ]);
      return response()->json(['message' => 'Atlikta!', 'success' => 'Nauja grupė sėkmingai sukurta.'], 200);

    }

    public function groups(){
      $groups = ItemGroup::get();
      return response()->json($groups);
    }

    public function rename(RenameItemGroupRequest $request){

        if(ItemGroup::find($request->id)->update(['ItemGroupName' => $request->name]))
            return response()->json(['message' => 'Atlikta!', 'success' => 'Grupė sėkmingai pervadinta.'], 200);
        else
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
    }

    public function changeImage(GroupChangeImageRequest $request){

      if($request->image == null)
        $image = null;
      else{
        $image = $request->image['dataUrl'];
      }

        if(ItemGroup::find($request->id)->update(['ItemGroupImage' => $image]))
            return response()->json(['message' => 'Atlikta!', 'success' => 'Grupės nuotrauka sėkmingai pakeista.'], 200);
        else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]],422);
    }

    public function delete($id){
         $items = Item::where('ItemGroupID', $id)->existing()->with('lastWithdrawal', 'lastSuspention', 'lastReservation')->get();
        foreach($items as $item){
            if($this->GetItemState($item) != "Sandėlyje"){
                return response()->json(['message' => 'Klaida',
                                                            'errors' => ['name' => [$item->ItemName.' negrąžintas į sandėlį, todėl įrankių grupė nebuvo ištrinta.']]], 422);
            }
        }
        Item::where('ItemGroupID', $id)->deleteItem();
        ItemGroup::destroy($id);
        return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankių grupė sėkmingai ištrinta.'],200);
    }

    public function get($id){
        $user = ItemGroup::find($id);
        return response()->json($user);
    }
}
