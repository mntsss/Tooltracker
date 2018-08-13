<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateItemGroupRequest;

use Illuminate\Support\Facades\Validator;
use App\ItemGroup;
use Auth;

class ItemGroupController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }
    public function create(CreateItemGroupRequest $request){

      if(ItemGroup::where('ItemGroupName', $request->name)->exists()){
        return response(['error' => 'Įrankių grupė tokiu pavadinimu jau yra!']);
      }
      if($request->image != ""){
        $image = $request->file('image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/media/uploads/');
        $image->move($destinationPath, $input['imagename']);
      }else {
        $input['imagename'] = "";
      }
      // $imageName = time().'.'.$request->image->getClientOriginalExtension();
      // $path = $request->image->storeAs('media/uploads', $imageName);

      ItemGroup::create([
        'ItemGroupName' => $request->name,
        'ItemGroupImage' => $input['imagename']
      ]);
      return response()->json([ 'status' => 'success']);

    }

    public function getGroups(){
      $groups = ItemGroup::get();
      return response()->json($groups);
    }

    public function getInfo($id){
        return response()->json(ItemGroup::with('items')->find($id));
    }
}
