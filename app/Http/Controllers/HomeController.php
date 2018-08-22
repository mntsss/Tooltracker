<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use Carbon\Carbon;
use App\ListVehicle;
use App\ItemGroup;
use App\Item;

use App\Events\ReceivedCode;

class HomeController extends Controller
{
    public function __construct()
    {
      // $this->middleware('auth');
    }

    public function home()
    {
      /*$param['itemGroups'] = ItemGroup::client()->get();
      $items = [];
      foreach($param['itemGroups'] as $group){
          $items[$group->ItemGroupID] = Item::client()->where('ItemGroupID', $group->ItemGroupID)->get()->toArray();
      }
      $param['items'] = json_encode($items);*/
      return view('layouts/main')->with('param', []);
    }

    public function sendCode($code){
      broadcast(new ReceivedCode($code));
    }
}
