<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class AddObjectRequest extends FormRequest
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
           'name' => 'required|string|min:3|max:125',
           'user' => 'required|exists:users,UserID'
         ];
     }
     public function messages(){
       return [
         'name.required' => 'Neįvestas objekto pavadinimas.',
         'name.string' => 'Neįvestas objekto pavadinimas.',
         'name.min' => 'Objekto pavadinimas negali būti trumpesnis nei 3 simboliai.',
         'name.max' => 'Objekto pavadinimas negali būti ilgesnis nei 125 simboliai.',
         'user.required' => 'Nepavyko identifikuoti vartotojo, kurį norima priskirti objektui kaip darbų vygdytoją. Bandykite dar kartą.',
         'user.exists' => 'Nepavyko identifikuoti vartotojo, kurį norima priskirti objektui kaip darbų vygdytoją, arba pasirinktas vartotojas sistemoje nebeegzistuoja. Bandykite dar kartą.'
       ];
     }
}
