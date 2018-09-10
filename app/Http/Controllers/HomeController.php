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

    public function sendCode($code){
      broadcast(new ReceivedCode($code));
    }
}
