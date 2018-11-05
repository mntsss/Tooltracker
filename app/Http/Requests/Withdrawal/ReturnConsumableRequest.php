<?php

namespace App\Http\Requests\Withdrawal;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ReturnConsumableRequest extends FormRequest
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
             'id' => 'required|exists:items,ItemID',
             'quantity' => 'required|numeric|min:1',
             'objectID' => 'required|exists:objects,ObjectID'
         ];
     }

     public function messages(){
       return [
         'id.required' => 'Nepavyko identifikuoti grąžinamo įrankio. Bandykite dar kartą.',
         'id.exists' => 'Nepavyko identifikuoti grąžinamo įrankio. Bandykite dar kartą.',
         'quantity.required' => 'Nenurodytas grąžinamų įrankių / daiktų kiekis!',
         'quantity.min' => 'Grąžinamas kiekis negali būti mažesnis negu 1.',
         'objectID.required' => 'Nepavyko identifikuoti objekto iš kurio grąžinami įrankiai.',
         'objectID.exists' => 'Nepavyko identifikuoti objekto iš kurio grąžinami įrankiai.',
       ];
     }
}
