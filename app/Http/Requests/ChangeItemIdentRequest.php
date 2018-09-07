<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ChangeItemIdentRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->UserRole == "Administratorius";
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function rules()
     {
         return [
             'id' => 'required|numeric',
             'ident' => 'sometimes|max:25'
         ];
     }
     public function messages(){
       return [
         'ident.max' => 'Įrankio pavadinimas negali būti ilgesnis nei 25 simboliai.',
         'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
         'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.'
       ];
     }
}
