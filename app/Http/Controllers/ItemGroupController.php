<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateItemGroupRequest;
use App\Http\Requests\RenameItemGroupRequest;

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

      if(ItemGroup::where('ItemGroupName', $request->name)->exists()){
        return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Grupė tokiu pavadinimu jau yra.']]], 422);
      }
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
      ItemGroup::create([
        'ItemGroupName' => $request->name,
        'ItemGroupImage' => $input['imagename']
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

    public function changeImage(Request $request){

        Validator::make($request->all(), [ 'id' => 'required|numeric'], [
          'id.required' => 'Įrankių grupė nežinoma. Apie klaidą praneškina administratoriui.',
          'id.numeric' => 'Kažkur įvyko klaida identifikuojant įrankių grupę. Apie klaidą praneškite administratoriui.'
        ])->validate();

        if(!is_null($request->image) && $request->image != "null" && $request->image != ""){
          Validator::make($request->all(), ['image' =>'image|mimes:jpg,jpeg,gif,png|max:4096'], [
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

        if(ItemGroup::find($request->id)->update(['ItemGroupImage' => $input['imagename']]))
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
}
