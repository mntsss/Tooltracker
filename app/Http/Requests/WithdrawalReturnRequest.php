<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class WithdrawalReturnRequest extends FormRequest
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
            'id' => 'required|exists:item_withdrawals,ItemWithdrawalID',
            'code' => 'required|exists:users,UserRFIDCode',
            'quantity' => 'required|numeric|min:1'
        ];
    }

    public function messages(){
      return [
        'id.required' => 'Nepavyko identifikuoti grąžinamo įrankio. Bandykite dar kartą.',
        'id.exists' => 'Įrankis nėra naudojamas, bandykite perkrauti puslapį, patikrinkite ar įrankis negrąžintas į sandėlį.',
        'code.required' => 'Nerasta administratoriaus identifikacinė kortelė.',
        'code.exists' => 'Nuskaityta kortelė nepriskirta jokiam vartotojui!',
        'quantity.required' => 'Nenurodytas grąžinamų įrankių / daiktų kiekis!',
        'quantity.min' => 'Grąžinamas kiekis negali būti mažesnis negu 1.'
      ];
    }
}
