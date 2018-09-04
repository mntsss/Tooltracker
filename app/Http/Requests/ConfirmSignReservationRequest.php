<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ConfirmSignReservationRequest extends FormRequest
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
          'sign' => 'required|string|max:500000',
        ];
    }
    public function messages(){
      return [
        'sign.max' => 'Parašas per didelis. Bandykite perkrauti puslapį, jei klaida kartojasi, susisiekite su administracija.',
        'sign.required' => 'Parašas privalomas, jis naudojamas kaip patvirtinimas, kad vartotojas gavo įrankius.',
        'id.required' => 'Nepavyko identifikuoti rezervacijos. Bandykite perkrauti puslapį, jei klaida kartojasi, susisiekite su administracija.'
      ];
    }
}
