<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'oldpass' => 'sometimes|string|max:85',
            'password' => 'required|string|min:6|confirmed',
            'id' => 'sometimes|numeric|exists:users,UserID'

        ];
    }

    public function messages(){
        return [
            'oldpass.max' => 'Senas slaptažodis per ilgas.',
            'password.required' => 'Naujas slaptažodis neįvestas.',
            'password.min' => 'Naujas slaptažodis negali būti trumpesnis nei 6 simboliai.',
            'password.confirmed' => 'Slaptažodžiai nesutampa.',
            'id.exists' => 'Toks vartotojas sistemoje neegzistuoja'
        ];
    }
}
