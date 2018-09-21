<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ItemRequest extends FormRequest
{
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
        'id' => 'sometimes|numeric|exists:items,ItemID',
        'name' => 'sometimes|string|min:3|max:40',
        'code' => 'sometimes|nullable|string|max:30|unique:rfid_codes,Code',
        'nocode' => 'sometimes|boolean',
        'acquired' => 'sometimes|nullable|max:150',
        'consumable' => 'sometimes|nullable|boolean',
        'warranty_date' => 'sometimes|nullable|date',
        'purchase_date' => 'sometimes|nullable|date',
        'groupID' => 'sometimes|numeric|exists:item_groups,ItemGroupID',
        'idnumber' => 'sometimes|string|nullable|max:190',
        'image' => 'sometimes|nullable',
        'image.dataUrl' => 'sometimes|string|max:500000',
        'image.name' => 'sometimes|string|max:128',
        'note' => 'sometimes|nullable|string|max:500'
      ];
  }

  public function messages(){
    return [
      'id.numeric' => 'Nepavyko identifikuoti įrankio. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
      'id.exists' => 'Nepavyko identifikuoti įrankio. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
      'name.required' => 'Neįvestas įrankio pavadinimas!',
      'name.string' => 'Neteisingas įrankio pavadinimo formatas.',
      'acquired.max' => 'Lauko "Įsigyta iš" reikšmė negali viršyti 150 simbolių!',
      'name.min' => 'Įrankio pavadinimas negali būti trumpesnis nei 3 simboliai',
      'name.max' => 'Įrankio pavadinimas negali būti ilgesnis nei 40 simbolių!',
      'consumable.boolean' => 'Nesuprantama "Suvartojama" pasirinkta reikšmė.',
      'code.string' => 'Netinkamas nuskaityto čipo kodo formatas!',
      'code.max' => 'Klaidingai nuskaitytas čipo kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą kortelę.',
      'code.unique' => 'Identifikacinis čipas jau priskirta kitam įrankiui! Nuskaitykite naują čipą.',
      'warranty_date.date' => 'Netinkamas garantinio laikotarpio formatas.',
      'purchase_date.date' => 'Neteisingas įsigijimo datos formatas.',
      'groupID.exists' => 'Nepavyko rasti nurodytos įrankių grupės. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
      'image.dataUrl.max' => 'Nuotraukos dydis per didelis. Kameros nustatymuose sumažinkite nuotraukų dimensijas ar kokybę.',
      'image.name.max' => 'Nuotraukos pavadinimo formatas netinkamas.',
      'idnumber.max' => 'Įrankio identifikacinis numeris negali viršyti 190 simbolių.',
      'note.max' => 'Įrankio komentaras negali viršyti 500 simbolių'
    ];
  }
}
