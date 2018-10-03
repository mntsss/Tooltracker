<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddObjectRequest;
use App\Http\Requests\ObjectForemanRequest;
use App\Http\Controllers\Controller;
use App\CObject;
use App\Item;
use App\ObjectForeman;

class ObjectController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function listObjects(){
        $objects = CObject::where('ObjectFinished', false)->with(['user','itemWithdrawals' => function ($quer) {
            $quer->where('ItemWithdrawalReturned', false)->with(['item' => function($q){
              $q->with([
                'lastWithdrawal' => function($query){ $query->with(['user', 'object']);},
                'lastSuspention' => function($query){ $query->with(['user']);},
                'lastReservation',
                'images',
                'itemGroup']);
            }]);
        }, 'rented' => function($q){ $q->with('cobject');}])->get();

        return response()->json($objects, 200);
    }
    public function closedObjects(){
        $objects = CObject::where('ObjectFinished', true)->with(['user','itemWithdrawals' => function ($quer) {
            $quer->where('ItemWithdrawalReturned', false)->with(['item' => function($q){
              $q->with(['lastWithdrawal' => function($query){ $query->with(['user', 'object']);}, 'lastSuspention' => function($query){ $query->with(['user']);}, 'lastReservation', 'images']);
            }]);
        }, 'rented' => function($q){ $q->with('cobject');}])->get();

        return response()->json($objects, 200);
    }
    public function add(AddObjectRequest $request){
        if(CObject::create([
            'ObjectName' => $request->name,
            'UserID' => $request->user
        ]))
            return response()->json(['message' => 'Atlikta', 'success' => 'Objektas sėkmingai pridėtas!'], 200);
        else {
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
        }
    }

    public function assignForeman(ObjectForemanRequest $request){
        if(CObject::find($request->objectID)->ObjectFinished)
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Negalima priskirti naujų darbų vygdytojų uždarytam objektui!']]], 422);

        if(ObjectForeman::create(['UserID' => $request->userID, 'ObjectID' => $request->objectID]))
            return response()->json(['message' => 'Atlikta', 'success' => 'Darbų vygdytojas priskirtas objektui.'], 200);
        else {
            return response()->json(['message'=>'Klaida', 'errors'=> ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
        }
    }

    public function removeForeman(ObjectForemanRequest $request){
        ObjectForeman::where('UserID', $request->userID)->where('ObjectID', $request->ObjectID)->update(['ForemanRemoved' => true]);
            return response()->json(['message' => 'Atlikta', 'success' => 'Darbų pašalintas iš objekto.'], 200);
    }
}
