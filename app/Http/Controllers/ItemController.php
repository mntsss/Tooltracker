<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemGroup;
use Auth;

class ItemController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function items($groupId){
      $itms = ItemGroup::find($groupId)->client()->with('items');
      dd($itms);
    }

    public function addItem(Request $request){

    }
}
