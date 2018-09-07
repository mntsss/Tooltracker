<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class EditItemNoteRequest extends FormRequest
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
          'id' => 'required|numeric',
          'note' => 'sometimes|max:500'
      ];
  }
  public function messages(){
    return [
      'note.max' => 'Įrankio komentaras negali būti ilgesnis nei 500 simbolių.',
      'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
      'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.'
    ];
  }
}
