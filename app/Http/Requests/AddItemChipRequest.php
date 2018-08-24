<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class AddItemChipRequest extends FormRequest
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
          'code' => 'nullable|string|max:30|unique:rfid_codes,Code',
        ];
    }

    public function messages(){
      return [
        'id.required' => 'Nepavyko identifikuoti įrankio kuriam norima priskirti čipą. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
        'code.max' => 'Klaidingai nuskaitytas čipo kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą čipą.',
        'code.unique' => 'Identifikacinis čipas jau priskirta kitam įrankiui! Nuskaitykite naują čipą.'
      ];
    }
}
