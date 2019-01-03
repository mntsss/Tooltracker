<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class StorageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->UserRole === "Administratorius";
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'id' => 'sometimes|numeric|exists:storages,id',
          'name' => 'sometimes|string|max:40|min:3',
        ];
    }

    public function messages()
    {
      'id.numeric' => 'Įvyko klaida!',
      'id.exists' => 'Sandėlis nerastas!',
      'name.string' => 'Sandėlio pavadinimas netinkamas.',
      'name.max' => 'Sandėlio pavadinimas negali viršyti 40 simbolių.',
      'name.min' => 'Sandėlio pavadinimas negali būti trumpesnis nei 3 simboliai.',
    }
}
