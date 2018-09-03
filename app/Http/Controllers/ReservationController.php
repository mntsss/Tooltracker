<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\ConfirmCardReservationRequest;
use App\Http\Requests\CreateAssignReservationRequest;
use App\Reservation;
use App\ReservationItem;
use App\ItemImage;
use App\ItemWithdrawal;
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

          if($item['item']['ItemDeleted']){
              return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įrankis '.$item['item']['ItemName'].' yra ištrintas, ir negali būti pridėtas į rezervaciją!']]], 422);
          }

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

    public function list(){
      $reservations = Reservation::Active()->where('UserID', Auth::user()->UserID)->with(['items' => function($query){
        $query->with('item');
    }, 'cobject' => function($query){ $query->with('user');}])->get();
      return response()->json($reservations, 200);
    }

    public function removeItemFromReservation(Request $request){
        $reservation = Reservation::find($request->item['ReservationID']);
        if($reservation->ReservationDelivered)
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Negalima ištrinti įrankių iš jau pristatytų rezervacijų...']]], 422);
        if(ReservationItem::destroy($request->item['ReservationItemID']))
            return response()->json(['message'=> 'Atlikta!', 'success' => 'Įrankis panaikintas iš rezervacijos!'],200);
        else {
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
        }
    }

    public function deleteReservation($id){
        $reservation = Reservation::find($id);
        if($reservation->ReservationDelivered)
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Negalima ištrinti jau pristatytų rezervacijų...']]], 422);

        ReservationItem::where('ReservationID', $id)->delete();
        if($reservation->delete()){
            return response()->json(['message'=> 'Atlikta!', 'success' => 'Rezervacija sėkmingai ištrinta, visi rezervacijos įrankiai perkelti į sandėlį.'],200);
        }
        else {
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida bandant ištrinti rezervaciją. Bandykite dar kartą, jei klaida kartojasi, susisiekite su administratoriumi.']]], 422);
        }
    }

    public function confirmReservationWithCard(ConfirmCardReservationRequest $request){
        $reservation = Reservation::with(['items' => function($query){ $query->with('image');}, 'cobject' => function($query){ $query->with('user');}])->find($request->id);

        if($reservation->ReservationDelivered)
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Rezervacija jau pristatyta! Greičiausiai kažkur įsivėlė klaida, pabandykite perkrauti puslapį.']]], 422);


        if($request->code != $reservation->cobject->user->UserRFIDCode)
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Nuskaityta neteisinga kortelė. Norėdami patvirtinti rezervaciją, nuskaitykite objekto, kuriam rezervacija sukurta, darbų vykdytojo identifikacinę kortelę.']]], 422);
        else{
            foreach($reservation->items as $item){
                $withdrawal = ItemWithdrawal::create([
                    'ItemWithdrawalQuantity' => $item->ReservationItemQuantity,
                    'UserID' => $reservation->cobject->user->UserID,
                    'ObjectID' => $reservation->ObjectID,
                    'ItemID' => $item->ItemID
                ]);
                if($item->image){
                    $image = ItemImage::find($item->image->ImageID);
                    if($image)
                        $image->update(['ItemWithdrawalID', $withdrawal->ItemWithdrawalID]);
                }
            }
            $reservation->ReservationDelivered = true;
            $reservation->ReservationRecipientUserID = $reservation->cobject->user->UserID;
            $reservation->save();

            return response()->json(['message' => 'Atlikta!', 'success' => "Rezervuoti įrankiai perduoti vartotojui ".$reservation->cobject->user->Username.", rezervacija patvirtinta."], 200);
        }
    }

    public function createAssignmentReservation(CreateAssignReservationRequest $request){

    }
}
