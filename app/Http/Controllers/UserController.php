<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\AddUserCardRequest;
use App\Http\Requests\RestoreUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;
use Hash;
use App\User;
use App\Code;

class UserController extends Controller
{
    public function __construct(){
      //$this->middleware('auth');
    }


    public function me(){
        Auth::user()->UserLastSeen = date('Y-m-d H:i:s');
        Auth::user()->save();
        return response()->json(Auth::user(),200);
    }

    public function listUsers(){
        $users = User::existing()->with('managedStorages')->get();
        return response()->json($users, 200);
    }

    public function deletedUsers(){
      $users = User::getDeleted()->get();
      return response()->json($users, 200);
    }

    public function changePasswordSubmit(ChangePasswordRequest $request)
    {
        if($request->id){
            if(Auth::user()->UserRole != "Administratorius")
                return response()->json(['message'=> 'Klaida','errors'=>['name'=>['Šiam veiksmui neturite teisių!']]]);
            User::find($request->id)->update(['password' => Hash::make($request->password)]);
            return response()->json(['message'=> 'Atlikta', 'success'=>'Slaptažodis pakeistas sėkmingai.']);
        }else
        {
            if(!Hash::check($request->oldpass, Auth::user()->password))
            {
                return response()->json(['message'=>'Klaida', 'errors'=>['name'=>['Senas slaptažodis neteisingas.']]], 422);
            }
            User::find(Auth::user()->id)->update(['password' => Hash::make($request->password)]);

            return response()->json(['message'=> 'Atlikta', 'success'=>'Slaptažodis pakeistas sėkmingai.']);
        }

    }

    public function create(CreateUserRequest $request){
      if(Code::where('Code', $request->code)->exists())
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Kortelės ar čipo kodas jau naudojamas sistemoje ir negali būti priskirtas dar kartą! Bandykite kitą kortelę.']] ],422);

      if($request->code == null && !$request->nocode)
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Serveris negavo kortelės kodo, ir varnelė dėl vartotojo registravimo be kortelės nebuvo pažymėta. Bandykite dar kartą, jeigu klaida išlieka - susisiekite su administracija.']]], 422);

      if($request->nocode)
        $request->code = null;
      if(User::create([
        'email' => $request->email,
        'Username' => $request->username,
        'UserRole' => $request->role,
        'UserPhone' => $request->phone,
        'code' => $request->code,
        'password' => Hash::make($request->password)
      ]))
        return response()->json(['message' => 'Atlikta!', 'success' => 'Naujas vartotojas sėkmingai užregistruotas!'], 200);
      else
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
    }

    public function edit(EditUserRequest $request){
      if(User::find($request->id)->update([
        'Username' => $request->username,
        'UserPhone' => $request->phone
      ]))
        return response()->json(['message' => 'Atlikta!', 'success' => 'Vartotojo informacija sėkmingai pakeista!'], 200);
      else
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);

    }

    public function delete($id){
      $withdrawalsCheck = User::find($id)->withdrawals()->Active()->exists();
      $reservationCheck = User::find($id)->reservations()->Active()->exists();
      if($withdrawalsCheck)
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => [ 'Vartotojas turi naudojamų arba įšaldytų įrankių!']]], 422);
      else if($reservationCheck)
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => [ 'Vartotojas turi aktyvių rezervacijų!']]], 422);
      else {
        if(User::find($id)->update(['UserDeleted' => true, 'password' => 'deleted', 'code' => null]))
          return response()->json(['message' => 'Atlikta!', 'success' => 'Vartotojas sėkmingai ištrintas.'],200);
        else
          return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
      }
    }

    public function addcard(AddUserCardRequest $request){
      if(Code::where('Code', $request->code)->exists())
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Kortelės ar čipo kodas jau naudojamas sistemoje ir negali būti priskirtas dar kartą! Bandykite kitą kortelę.']] ],422);

      if(User::find($request->id)->update(['code' => $request->code]))
        return response()->json(['message' => 'Atlikta!', 'success' => 'Vartotojui sėkmingai priskirta nauja identifikacinė kortelė.'],200);
      else
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
    }

    public function restoreuser(RestoreUserRequest $request){
      if(User::find($request->id)->update(['UserDeleted' => false, 'password' => Hash::make($request->password)]))
        return response()->json(['message' => 'Atlikta', 'success' => 'Vartotojas sėkmingai atkurtas!'], 200);
      else {
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
      }
    }

    public function getWithdrawals($id){
        $user = User::with([
            'withdrawals' => function($q){ $q->Active()->with(['item' => function($q){ return $q->with('itemGroup');}]);}
            ])->find($id);
        if($user)
            return response()->json($user, 200);
        else
            return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
    }
}
