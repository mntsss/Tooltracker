<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class RestoreUserRequest extends FormRequest
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
            'password' => 'required|string|min:6|max:50|confirmed',
            'id' => 'required'
        ];
    }
    public function messages(){
      return [
        'password.required' => 'Neįvestas slaptažodis!',
        'password.min' => 'Slaptažodis per trumpas! Saugumo sumetimais slaptažodis negali būti trumpesnis nei 6 simboliai.',
        'password.max' => 'Slaptažodis negali būti ilgesnis nei 50 simbolių!',
        'password.confirmed' => 'Slaptažodžiai nesutampa!',
        'id.required' => 'Nepavyko identifikuoti vartotojo kurį bandoma atkurti. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.'
      ];
    }
}
