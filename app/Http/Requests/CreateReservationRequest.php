<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class CreateReservationRequest extends FormRequest
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
            'objectID' => 'sometimes|exists:objects,ObjectID',
            'userID' => 'required|numeric|exists:users,id',
            'items.*.item.ItemID' => 'required|distinct|exists:items,ItemID',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.image' => 'nullable',
            'items.*.image.dataUrl' => 'sometimes|string|max:5000000',
            'items.*.image.name' => 'sometimes|string|max:128'
        ];
    }

    public function messages(){
      return [
        'objectID.exists' => 'Objektas, kuriam bandoma priskirti rezervaciją, nerastas duomenų bazėje. Patikrinkite ar objektas nebuvo ištrintas ir bandykite dar kartą.',
        'userID.required' => 'Nenurodytas vartotojas!',
        'userID.numeric' => 'Neteisingas formatas.',
        'userID.exists' => 'Nepavyko identifikuoti vartotojo!',
        'items.*.item.ItemID.required' => 'Rezervacija negali turėti neidentifikuojamų įrankių!',
        'items.*.item.ItemID.distinct' => 'Į rezervaciją negalima pridėti to paties įrankio kelis kartus!',
        'items.*.item.ItemID.exists' => 'Įrankio nepavyko identifikuoti duomenų bazėje. Bandykite dar kartą!',
        'items.*.quantity.required' => 'Nežinomas rezervacijos įrankių kiekis!',
        'items.*.quantity.min' => 'Neteisingai nurodytas įrankių kiekis! Negali būti mažiau negu 1.',
        'items.*.image.dataUrl.max' => 'Nuotraukos dydis per didelis. Kameros nustatymuose sumažinkite nuotraukų dimensijas ar kokybę.',
        'items.*.image.name.max' => 'Nuotraukos pavadinimo formatas netinkamas.'
      ];
    }
}
