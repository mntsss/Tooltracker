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

          $reservationItem = ReservationItem::create([
              'ReservationID' => $reservation->ReservationID,
              'ItemID' => $item['item']['ItemID'],
              'ReservationItemQuantity' => $item['quantity']
          ]);

        if($item['image'] != null){
          //get image name
          $explExtension = explode('.', $item['image']['name']);
          $extension = end($explExtension);
          $imageName = $explExtension[0].'_'.time().'.'.$extension;
          //get image code
          $imageExpl = explode(',',$item['image']['dataUrl']);
          $base64 = end($imageExpl);
          //saving image as a file

          if(file_put_contents(public_path(env('ITEMS_IMAGE_ROUTE')).$imageName, base64_decode($base64))){
              if(!ItemImage::create([
                  'ImageName' => $imageName,
                  'ItemID' => $item['item']['ItemID'],
                  'ReservationItemID' => $reservationItem->ReservationItemID
              ])){
                  return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
              }
          }
          else {
              return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida išsaugant nuotrauką failų sistemoje. Susisiekite su administratoriumi.']]], 422);
          }
        }
      }
      return response()->json(['message'=> 'Atlikta!', 'success' => 'Rezervacija sėkmingai sukurta!'],200);
    }
}
