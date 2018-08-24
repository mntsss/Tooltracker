<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\AddUserCardRequest;
use App\Http\Requests\RestoreUserRequest;

use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;
use Hash;
use App\User;
use App\RfidCode;

class UserController extends Controller
{
    public function __construct(){
      //$this->middleware('auth');
    }

    public function listUsers(){
        $users = User::existing()->get();
        return response()->json($users, 200);
    }

    public function deletedUsers(){
      $users = User::getDeleted()->get();
      return response()->json($users, 200);
    }

    public function changePasswordSubmit(Request $request)
    {
      Validator::make($request->all(),
      ['oldpass' => 'required|string|min:6|max:85',
        'password' => 'required|string|min:6|confirmed',])->validate();

        if(!Hash::check($request->oldpass, Auth::user()->password))
        {
            $request->session()->flash('error', 'Senas slaptažodis įvestas neteisingai! ');
            return redirect()->back();
        }
      User::find(Auth::user()->id)->update(['password' => Hash::make($request->password)]);

        $request->session()->flash('success', 'Slaptažodis pakeistas sėkmingai!');
        return redirect()->route("active");
    }

    public function create(CreateUserRequest $request){
      if(RfidCode::where('Code', $request->code)->exists())
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
        'UserRFIDCode' => $request->code,
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
        if(User::find($id)->update(['UserDeleted' => true, 'password' => 'deleted', 'UserRFIDCode' => null]))
          return response()->json(['message' => 'Atlikta!', 'success' => 'Vartotojas sėkmingai ištrintas.'],200);
        else
          return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Apie klaidą praneškite administracijai.']]], 422);
      }
    }

    public function addcard(AddUserCardRequest $request){
      if(RfidCode::where('Code', $request->code)->exists())
        return response()->json(['message' => 'Klaida!', 'errors' => ['name' => ['Kortelės ar čipo kodas jau naudojamas sistemoje ir negali būti priskirtas dar kartą! Bandykite kitą kortelę.']] ],422);

      if(User::find($request->id)->update(['UserRFIDCode' => $request->code]))
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
}
