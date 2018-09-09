<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class DeleteItemRequest extends FormRequest
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
         ];
     }
     public function messages(){
       return [
         'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
         'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.',
         'id.exists' => 'Nepavyko identifikuoti įrankio.'
       ];
     }
    }
