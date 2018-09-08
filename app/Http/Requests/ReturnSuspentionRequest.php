<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ReturnSuspentionRequest extends FormRequest
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
            'id' => 'required|numeric|exists:items,ItemID'
        ];
    }

    public function messages(){
        return [
            'id.required' => 'Negauta įrankio informacija! Bandykite dar kartą, jeigu klaida kartojasi, susisiekite su administracija.',
            'id.exists' => 'Nepavyko identifikuoti įrankio. Perkraukite puslapį ir bandykite dar kartą. Jeigu klaida kartojasi, susisiekite su administracija.'
        ];
    }
}
