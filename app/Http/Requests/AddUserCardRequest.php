<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class AddUserCardRequest extends FormRequest
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
            'code' => 'required|string|max:30|unique:users,code',
        ];
    }
    public function messages(){
      return [
        'id.required' => 'Nepavyko identifikuoti vartotojo kurio duomenys keičiami. Bandykite dar kartą, jeigu klaida išlieka, susisiekite su administracija.',
        'code.max' => 'Klaidingai nuskaitytas kortelės kodas! Pabandykite dar kartą, jei klaida kartojasi, bandykite kitą kortelę.',
        'code.unique' => 'Identifikacinė kortelė jau priskirta kitam vartotojui! Nuskaitykite naują kortelę.'
      ];
    }
}
