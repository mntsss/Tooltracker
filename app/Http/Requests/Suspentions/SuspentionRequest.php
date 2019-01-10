<?php

namespace App\Http\Requests\Suspentions;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class SuspentionRequest extends FormRequest
{
  public function authorize()
  {
      return Auth::user()->UserRole == "Administratorius";
  }

  public function rules()
  {
      return [
          'id' => 'required|numeric|exists:items,ItemID',
          'note' => 'sometimes|max:500',
          'code' => 'sometimes|string|max:30|exists:users,code'
      ];
  }
  public function messages(){
    return [
      'note.max' => 'Komentaras negali būti ilgesnis nei 500 simbolių.',
      'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
      'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.',
      'id.exists' => 'Įrankio nepavyko rasti duomenų bazėje.',
      'code.max' => 'Klaidingai nuskaitytas kortelės kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą kortelę.',
      'code.exists' => 'Identifikacinė kortelė nepriskirta jokiam vartotojui!'
    ];
  }
}
