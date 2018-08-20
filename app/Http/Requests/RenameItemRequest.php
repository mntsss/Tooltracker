<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class RenameItemRequest extends FormRequest
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
            'name' => 'string|min:3|max:25|required'
        ];
    }
    public function messages(){
      return [
        'name.required' => 'Neįvestas įrankio pavadinimas.',
        'name.string' => 'Neįvestas įrankio pavadinimas.',
        'name.min' => 'Įrankio pavadinimas negali būti trumpesnis nei 3 simboliai.',
        'name.max' => 'Įrankio pavadinimas negali būti ilgesnis nei 25 simboliai.',
        'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
        'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.'
      ];
    }
}
