<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ItemSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->UserDeleted == false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'query' => 'string|max:50|required'
        ];
    }
    public function messages(){
      return [
        'query.string' => 'Netinkamas užklausos formatas, kažkur įvyko klaida. Bandykite dar kartą, jeigu klaida kartojasi, susisiekite su administracija.',
        'query.max' => 'Paieškos užklausa negali būti ilgesnė negu 50 simbolių!',
        'query.required' => 'Paieškos užklausa tuščia.'
      ];
    }
}
