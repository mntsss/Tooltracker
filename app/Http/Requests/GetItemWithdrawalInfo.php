<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class GetItemWithdrawalInfo extends FormRequest
{

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
            'id' => 'required|exists:items,ItemID',
        ];
    }

    public function message(){
        return [
            'id.required' => 'Negauta grąžinamo įrankio informacija! Bandykite dar kartą, jeigu klaida kartojasi, susisiekite su administracija.',
            'id.exists' => 'Nepavyko identifikuoti įrankio. Perkraukite puslapį ir bandykite dar kartą. Jeigu klaida kartojasi, susisiekite su administracija.'
        ];
    }
}
