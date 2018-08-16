<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class CreateItemGroupRequest extends FormRequest
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
          'name' => 'required|string|min:3|max:25'
        ];
    }
    public function messages(){
      return [
        'name.required' => 'Neįvestas grupės pavadinimas.',
        'name.string' => 'Neįvestas grupės pavadinimas.',
        'name.min' => 'Grupės pavadinimas negali būti trumpesnis nei 3 simboliai.',
        'name.max' => 'Grupės pavadinimas negali būti ilgesnis nei 25 simboliai.'
      ];
    }
}
