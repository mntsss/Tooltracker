<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddObjectRequest;
use App\Http\Controllers\Controller;
use App\CObject;
use App\Item;

class ObjectController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function listObjects(){
        $objects = CObject::where('ObjectFinished', false)->with(['user','itemWithdrawals' => function ($quer) {
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
}
