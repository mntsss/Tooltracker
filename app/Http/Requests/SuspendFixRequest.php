<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class SuspendFixRequest extends FormRequest
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
          'id' => 'required|numeric|exists:items,ItemID',
          'note' => 'sometimes|max:500'
      ];
  }
  public function messages(){
    return [
      'note.max' => 'Komentaras negali būti ilgesnis nei 500 simbolių.',
      'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
      'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.',
      'id.exists' => 'Įrankio nepavyko rasti duomenų bazėje.'
    ];
  }
}
