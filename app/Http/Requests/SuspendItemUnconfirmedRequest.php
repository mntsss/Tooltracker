<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class SuspendItemUnconfirmedRequest extends FormRequest
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
            'id' => 'required|exists:item_withdrawals,ItemID|exists:items,ItemID',
            'note' => 'nullable|string|max:500'
        ];
    }
    public function messages(){
      return [
        'id.required' => 'Nepavyko identifikuoti įrankio, kurį norima įšaldyti. Bandykite dar kartą.',
        'id.exists' => 'Įrankis nerastas duomenų bazėje. Patikrinkite ar įrankis neištrintas ir bandykite vėl.',
        'note.string' => 'Netinkamas komentaro formatas!',
        'note.max' => 'Komentaras per ilgas - negali viršyti 500 simbolių.'
      ];
    }
}
