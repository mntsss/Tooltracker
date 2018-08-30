<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class GroupChangeImageRequest extends FormRequest
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
           'id' => 'required|numeric|exists:item_groups,ItemGroupID',
           'image' => 'nullable',
           'image.dataUrl' => 'sometimes|string|max:500000',
           'image.name' => 'sometimes|string|max:128'
         ];
     }
     public function messages(){
       return [
         'id.required' => 'Įrankių grupė nežinoma. Apie klaidą praneškina administratoriui.',
         'id.numeric' => 'Kažkur įvyko klaida identifikuojant įrankių grupę. Apie klaidą praneškite administratoriui.',
         'id.exists' => 'Įrankių grupė nežinoma. Apie klaidą praneškina administratoriui.',
         'image.dataUrl.max' => 'Nuotraukos dydis per didelis. Kameros nustatymuose sumažinkite nuotraukų dimensijas ar kokybę.',
         'image.name.max' => 'Nuotraukos pavadinimo formatas netinkamas.'
       ];
     }
   }
