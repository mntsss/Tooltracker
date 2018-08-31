<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ConfirmCardReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function authorize()
     {
         return Auth::user()->UserRole == "Administratorius";
     }

     /**
      * Get the validation rules that apply to the request.
      *
      * @return array
      */
     public function rules()
     {
         return [
           'id' => 'required',
           'code' => 'required|string|max:30|exists:users,UserRFIDCode',
         ];
     }

     public function messages(){
       return [
         'id.required' => 'Nepavyko identifikuoti rezervacijos, kurią bandoma patvirtinti. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
         'code.max' => 'Klaidingai nuskaitytas kortelės kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą čipą.',
         'code.required' => 'Negautas vartotojo identifikacinės kortelės kodas!',
         'code.exists' => 'Nuskaityta kortelė nepriskirta jokiam sistemoje registruotam vartotojui!'
       ];
    }
}
