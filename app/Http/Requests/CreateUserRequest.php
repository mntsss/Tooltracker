<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class CreateUserRequest extends FormRequest
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
            'email' => 'required|email|string|max:50|unique:users,email',
            'username' => 'required|string|max:50|unique:users,Username',
            'role' => 'required|string|max:50',
            'password' => 'required|string|min:6|max:50|confirmed',
            'phone' => 'sometimes|max:14',
            'code' => 'nullable|string|max:30|unique:users,code',
            'nocode' => 'required|boolean'
        ];
    }

    public function messages(){
      return [
        'email.required' => 'Neįvestas el. paštas!',
        'email.email' => 'Neteisingas el. paštas!',
        'email.max' => 'El. paštas negali būti ilgesnis nei 50 simbolių!',
        'email.unique' => 'Vartotojas tokiu el. paštu jau yra!',
        'username.required' => 'Neįvestas vartotojo vardas!',
        'username.max' => 'Vartotojo vardas negali būti ilgesnis nei 50 simbolių!',
        'username.unique' => 'Vartotojas tokiu vardu ir pavarde jau yra!',
        'role.required' => 'Nenurodytas vartotojo tipas!',
        'role.max' => 'Įvyko klaida nustatant vartotojo tipą!',
        'password.required' => 'Neįvestas slaptažodis!',
        'password.min' => 'Slaptažodis per trumpas! Saugumo sumetimais slaptažodis negali būti trumpesnis nei 6 simboliai.',
        'password.max' => 'Slaptažodis negali būti ilgesnis nei 50 simbolių!',
        'password.confirmed' => 'Slaptažodžiai nesutampa!',
        'phone.max' => 'Telefono numeris negali būti ilgesnis nei 14 simbolių',
        'code.max' => 'Klaidingai nuskaitytas kortelės kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą kortelę.',
        'code.unique' => 'Identifikacinė kortelė jau priskirta kitam vartotojui! Nuskaitykite naują kortelę.',
      ];
    }
}
