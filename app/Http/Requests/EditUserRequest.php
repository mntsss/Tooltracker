<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class EditUserRequest extends FormRequest
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
          'username' => 'required|string|max:50',
          'phone' => 'sometimes|max:14',
          'id' => 'required'
        ];
    }
    public function messages(){
      return [
        'username.required' => 'Neįvestas vartotojo vardas!',
        'username.max' => 'Vartotojo vardas negali būti ilgesnis nei 50 simbolių!',
        'username.unique' => 'Vartotojas tokiu vardu ir pavarde jau yra!',
        'phone.max' => 'Telefono numeris negali būti ilgesnis nei 14 simbolių',
        'id.required' => 'Nepavyko identifikuoti vartotojo kurio duomenys keičiami. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.'
      ];
    }
}
