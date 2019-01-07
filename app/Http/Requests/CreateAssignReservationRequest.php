<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class CreateAssignReservationRequest extends FormRequest
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

    public function rules()
    {
        return [
            'items' => 'required',
            'items.*.item.ItemID' => 'required|exists:items,ItemID',
            'user' => 'required',
            'user.UserID' => 'exists:users,id'
        ];
    }
    public function messages(){
      return [
        'items.required' => 'Rezervacija turi turėti priskiriamų įrankių!',
        'items.*.item.ItemID.required' => 'Nepavyko identifikuoti vieno iš įrankų.',
        'items.*.item.ItemID.exists' => 'Nepavyko identifikuoti vieno iš įrankų.',
        'user.required' => 'Privaloma nurodyti, kuriam vartotojui priskiriama rezervacija!',
        'user.UserID.exists' => 'Nurodytas vartotojas nerastas duomenų bazėje.'
      ];
    }
}
