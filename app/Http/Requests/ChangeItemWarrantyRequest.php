<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ChangeItemWarrantyRequest extends FormRequest
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
             'id' => 'required|numeric|exists:items,ItemID',
             'warranty' => 'sometimes|date|max:25'
         ];
     }
     public function messages(){
       return [
         'warranty.max' => 'Data negali būti ilgesnė nei 25 simboliai.',
         'warranty.date' => 'Neteisingas datos formatas',
         'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
         'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.',
         'id.exists' => 'Nepavyko identifikuoti įrankio.'
       ];
     }
    }
