<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class FindItemWithCodeRequest extends FormRequest
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
            'code' => 'required|string|max:50|exists:rfid_codes,Code'
        ];
    }

    public function messages(){
      return [
        'code.required' => 'Klaida serveriui siunčiant įrankio identifikacinį kodą. Bandykite dar kartą.',
        'code.string' => 'Klaida serveriui siunčiant įrankio identifikacinį kodą. Bandykite dar kartą.',
        'code.max' => 'Klaida nuskaitant įrankio identifikatorių. Bandykite dar kartą, patikrinkite ar veikia identifikacinis čipas.',
        'code.exists' => 'Identifikacinis čipas nepriskirtas jokiam įrankiui!'
      ];
    }
}
