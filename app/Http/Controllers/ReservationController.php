<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateReservationRequest;
use App\Reservation;
use App\ReservationItem;
use App\ItemImage;
use Auth;
class ReservationController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function create(CreateReservationRequest $request){
      $reservation = Reservation::create([
        'UserID' => Auth::user()->UserID,
        'ObjectID' => $request->objectID
      ]);

      foreach($request->items as $item){

        if($item->image != null && $item->image != 'null' && $item->image != ''){
          //get file extension
          $extension = explode(';',explode('/', $item->image)[1])[0];
          echo $extension;
        }
        /*$imageName = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/media/items/');
        file_put_contents('foo.png', base64_decode($str));*/
      }
    }
}
