<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ConfirmReturnSuspentionRequest extends FormRequest
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
          'code' => 'required|string|max:30|exists:users,UserRFIDCode',
      ];
  }
  public function messages(){
    return [
      'id.required' => 'Nepavyko identifikuoti įrankio. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
      'id.exists' => 'Nepavyko identifikuoti įrankio. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
      'code.max' => 'Klaidingai nuskaitytas kortelės kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą kortelę.',
      'code.exists' => 'Identifikacinė kortelė nepriskirta jokiam vartotojui!'
    ];
  }
}
