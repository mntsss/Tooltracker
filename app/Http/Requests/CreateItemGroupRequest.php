<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class CreateItemGroupRequest extends FormRequest
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
          'name' => 'required|string|min:3|max:50|unique:item_groups,ItemGroupName',
          'image' => 'nullable',
          'image.dataUrl' => 'sometimes|string|max:500000',
          'image.name' => 'sometimes|string|max:128'
        ];
    }
    public function messages(){
      return [
        'name.required' => 'Neįvestas grupės pavadinimas.',
        'name.string' => 'Neįvestas grupės pavadinimas.',
        'name.min' => 'Grupės pavadinimas negali būti trumpesnis nei 3 simboliai.',
        'name.max' => 'Grupės pavadinimas negali būti ilgesnis nei 50 simboliai.',
        'name.unique' => 'Įrankių grupė tokiu pavadinimu jau yra.',
        'image.dataUrl.max' => 'Nuotraukos dydis per didelis. Kameros nustatymuose sumažinkite nuotraukų dimensijas ar kokybę.',
        'image.name.max' => 'Nuotraukos pavadinimo formatas netinkamas.'
      ];
    }
}
