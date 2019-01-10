<?php

namespace App\Http\Controllers;

use App\Http\Services\ActionRecorderService;
use App\Http\Services\ImageService;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\ConfirmCardReservationRequest;
use App\Http\Requests\CreateAssignReservationRequest;
use App\Http\Requests\ConfirmSignReservationRequest;
use App\Reservation;
use App\ReservationItem;
use App\ItemImage;
use App\ItemWithdrawal;
use App\Http\Services\ValidationService;
use Auth;
class ReservationController extends Controller
{
    private $validationService;

    public function __construct(ValidationService $validationService){
      $this->middleware('auth');
      $this->validationService = $validationService;
    }

    public function create(CreateReservationRequest $request, ImageService $imageService, ActionRecorderService $actionRecorderService){

      $reservation = Reservation::create([
        'UserID' => Auth::user()->id,
        'ObjectID' => $request->objectID,
        'ReservationRecipientUserID' => $request->userID
      ]);

      foreach($request->items as $item){

          if($item['item']['status'] != Item::ITEM_IN_STORAGE){
              return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įrankis '.$item['item']['ItemName'].' ne sandėlyje, todėl negali būti pridėtas į rezervaciją!']]], 422);
          }

          $reservationItem = ReservationItem::create([
              'ReservationID' => $reservation->ReservationID,
              'ItemID' => $item['item']['id'],
              'ReservationItemQuantity' => $item['quantity']
          ]);

          $item = new Item();
          $item->fill($item);
          $actionRecorderService->record($item, Item::ITEM_RESERVED);


          if($item['image'] != null){
              $name = $imageService->save($item['image']);
              $image = new ItemImage();
              $image->item_id = $item->id;
              $image->reservation_item_id = $reservationItem->ReservationItemID;
              $image->reservation_id = $reservation->ReservationID;
              $image->name = $name;
              $image->save();
          }
      }
      return response()->json(['message'=> 'Atlikta!', 'success' => 'Rezervacija sėkmingai sukurta!'],200);
    }

    public function list(){
      $reservations = Reservation::Active()->where('UserID', Auth::user()->id)->with(['items' => function($query){
        $query->with(['item' => function($q){ $q->with('itemGroup');}]);
    }, 'cobject' => function($query){ $query->with(['foremen' => function($q){
      $q->with('user');
    }]);}, 'recipient'])->orderBy('created_at', 'DESC')->get();
      return response()->json($reservations, 200);
    }

    public function closed($userID = null, $from = null, $til = null)
    {
        $reservations = Reservation::where('ReservationDelivered', true)->with(['items' => function($query){
          $query->with('item');
      }, 'cobject' => function($query){ $query->with(['foremen' => function($q){
        $q->with('user');
      }]);}, 'recipient']);
      if($userID){
        if($userID === "all")
        {}
        else{
          $userID = (int)$userID;
          $reservations = $reservations->where('ReservationRecipientUserID', $userID);
        }
      }
      if($this->validationService->isDateStringValid($from)){
        $reservations = $reservations->where('updated_at', '>', $from);
      }
      if($this->validationService->isDateStringValid($til)){
        $reservations = $reservations->where('updated_at', '<', $til);
      }
      $reservations = $reservations->orderBy('updated_at', 'DESC')->get();
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
        $reservation = Reservation::with(['items' => function($query){ $query->with('image');}, 'recipient'])->find($request->id);

        if($reservation->ReservationDelivered)
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Rezervacija jau pristatyta! Greičiausiai kažkur įsivėlė klaida, pabandykite perkrauti puslapį.']]], 422);


        if($request->code != $reservation->recipient[0]->code)
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Nuskaityta neteisinga kortelė. Norėdami patvirtinti rezervaciją, nuskaitykite objekto, kuriam rezervacija sukurta, darbų vykdytojo identifikacinę kortelę.']]], 422);
        else{
            foreach($reservation->items as $item){
                $withdrawal = ItemWithdrawal::create([
                    'ItemWithdrawalQuantity' => $item->ReservationItemQuantity,
                    'UserID' => $reservation->recipient[0]->UserID,
                    'ObjectID' => $reservation->ObjectID,
                    'ItemID' => $item->ItemID
                ]);
                if($item->image){
                  $image = ItemImage::find($item->image->ImageID);
                  if($image)
                      $image->update(['ItemWithdrawalID'=> $withdrawal->ItemWithdrawalID]);
                }
            }
            $reservation->ReservationDelivered = true;
            $reservation->ReservationConfirmCardNr = $request->code;
            $reservation->save();

            return response()->json(['message' => 'Atlikta!', 'success' => "Rezervuoti įrankiai perduoti vartotojui ".$reservation->recipient[0]->Username.", rezervacija patvirtinta."], 200);
        }
    }

    public function confirmReservationWithSignature(ConfirmSignReservationRequest $request){
      $reservation = Reservation::with(['items' => function($query){ $query->with('image');}, 'recipient'])->find($request->id);

      if($reservation->ReservationDelivered)
          return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Rezervacija jau pristatyta! Greičiausiai kažkur įsivėlė klaida, pabandykite perkrauti puslapį.']]], 422);

      foreach($reservation->items as $item){
          $withdrawal = ItemWithdrawal::create([
              'ItemWithdrawalQuantity' => $item->ReservationItemQuantity,
              'UserID' => $reservation->recipient[0]->UserID,
              'ObjectID' => $reservation->ObjectID,
              'ItemID' => $item->ItemID
            ]);
          if($item->image){
            $image = ItemImage::find($item->image->ImageID);
            if($image)
                $image->update(['ItemWithdrawalID'=> $withdrawal->ItemWithdrawalID]);
          }
        }
        $reservation->ReservationDelivered = true;
        $reservation->ReservationConfirmSignature = $request->sign;
        $reservation->save();

        return response()->json(['message' => 'Atlikta!', 'success' => "Rezervuoti įrankiai perduoti vartotojui ".$reservation->recipient[0]->Username.", rezervacija patvirtinta."], 200);
    }
    public function createAssignmentReservation(CreateReservationRequest $request, ImageService $imageService, ActionRecorderService $actionRecorderService){
        $reservation = Reservation::create([
            'UserID' => Auth::user()->id,
            'ReservationRecipientUserID' => $request->userID
        ]);

        foreach($request->items as $item) {

            if ($item['item']['status'] != Item::ITEM_IN_STORAGE) {
                return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankis ' . $item['item']['ItemName'] . ' ne sandėlyje, todėl negali būti pridėtas į rezervaciją!']]], 422);
            }

            $reservationItem = ReservationItem::create([
                'ReservationID' => $reservation->ReservationID,
                'ItemID' => $item['item']['id'],
                'ReservationItemQuantity' => $item['quantity']
            ]);
            //dd($item['item']);
            $itemTemp = new Item();
            $itemTemp->fill($item['item']);
            $itemTemp->id = $item['item']['id'];
            $actionRecorderService->record($itemTemp, Item::ITEM_RESERVED);


            if ($item['image'] != null) {
                $name = $imageService->save($item['image']);
                $image = new ItemImage();
                $image->item_id = $item->id;
                $image->reservation_item_id = $reservationItem->ReservationItemID;
                $image->reservation_id = $reservation->ReservationID;
                $image->name = $name;
                $image->save();
            }
        }
      return response()->json(['message'=> 'Atlikta!', 'success' => 'Įrankių priskyrimo rezervacija sėkmingai išsaugota!'],200);
    }
}
