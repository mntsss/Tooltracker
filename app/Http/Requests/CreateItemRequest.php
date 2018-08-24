<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class CreateItemRequest extends FormRequest
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
          'name' => 'required|string|min:3|max:40',
          'code' => 'nullable|string|max:30|unique:rfid_codes,Code',
          'nocode' => 'required|boolean',
          'consumable' => 'nullable|boolean',
          'warranty_date' => 'nullable|date',
          'purchase_date' => 'nullable|date',
          'groupID' => 'required|numeric'
        ];
    }

    public function messages(){
      return [
        'name.required' => 'Neįvestas įrankio pavadinimas!',
        'name.string' => 'Neteisingas įrankio pavadinimo formatas.',
        'name.min' => 'Įrankio pavadinimas negali būti trumpesnis nei 3 simboliai',
        'name.max' => 'Įrankio pavadinimas negali būti ilgesnis nei 40 simbolių!',
        'consumable.boolean' => 'Nesuprantama "Suvartojama" pasirinkta reikšmė.',
        'code.string' => 'Netinkamas nuskaityto čipo kodo formatas!',
        'code.max' => 'Klaidingai nuskaitytas čipo kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą kortelę.',
        'code.unique' => 'Identifikacinis čipas jau priskirta kitam įrankiui! Nuskaitykite naują čipą.',
        'warranty_date.date' => 'Netinkamas garantinio laikotarpio formatas.',
        'purchase_date.date' => 'Neteisingas įsigijimo datos formatas.',
        'groupID' => 'Severis neranda įrankių grupės identifikacijos. Apie klaidą praneškite administratoriui.'
      ];
    }
}
