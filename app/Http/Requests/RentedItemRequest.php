<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class RentedItemRequest extends FormRequest
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

    public function rules()
    {
        return [
            'id' => 'sometimes|numeric|exists:rented_items,RentedItemID',
            'name' => 'sometimes|string|max:50|min:3',
            'note' => 'sometimes|nullable|string|max:500',
            'price' => 'sometimes|nullable|numeric|min:0.00|max:9999.99',
            'object' => 'sometimes|nullable|numeric|exists:objects,ObjectID',
            'rentDate' => 'sometimes|nullable|date|max:30'
        ];
    }

    public function messages(){

      return [
        'id.numeric' => 'Nepavyko identifikuoti įrankio.',
        'id.exists' => 'Įrankis nerastas nuomotų įrankių duomenų bazėje.',
        'name.string' => 'Įrankio pavadinimas netinkamas.',
        'name.max' => 'Įrankio pavadinimas negali viršyti 50 simbolių.',
        'name.min' => 'Įrankio pavadinimas negali būti trumpesnis nei 3 simboliai.',
        'note.string' => 'Įrankio komentaro formatas neteisingas.',
        'note.max' => 'Įrankio komentaras negali viršyti 500 simbolių.',
        'price.numeric' => 'Įrankio kainos formatas neteisingas.',
        'price.min' => 'Įrankio nuomos kaina negali būti neigiama.',
        'price.max' => 'Įrankio kaina netinkama (skaičiai nerealūs).',
        'object.numeric' => 'Nepavyko identifikuoti objekto.',
        'object.exists' => 'Objektas nerastas duomenų bazėje.',
        'rentDate.date' => 'Nuomos pradžios formatas netinkamas.',
        'rentDate.max' => 'Nuomos pradžios formatas netinkamas.'
      ];

    }
}
