<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ObjectForemanRequest extends FormRequest
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
            'userID' => 'required|numeric|exists:users,id',
            'objectID' => 'required|numeric|exists:objects,ObjectID'
        ];
    }

    public function messages(){
        return [
            'userID.required' => 'Nežinomas vartotojas.',
            'userID.numeric' => 'Klaida identifikuojant vartotoją!',
            'userID.exists' => 'Vartotojas sistemoje nerastas!',
            'objectID.required' => 'Nežinomas objektas.',
            'objectID.numeric' => 'Klaida identifikuojant objektą!',
            'objectID.exists' => 'Objektas sistemoje nerastas.'
        ];
    }
}
