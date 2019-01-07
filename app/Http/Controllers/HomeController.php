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
    }

    public function home()
    {
      return view('layouts/main');
    }

    public function sendCode($key, $user_id, $code){
      $api_key = env('NFC_MIDDLEWARE_KEY');
      if($api_key == $key)
        broadcast(new ReceivedCode($code, $user_id));
    }
}
