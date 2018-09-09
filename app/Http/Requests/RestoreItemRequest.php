<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class RestoreItemRequest extends FormRequest
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
             'groupID' => 'required|numeric|exists:item_groups,ItemGroupID'
         ];
     }
     public function messages(){
       return [
         'groupID.required' => 'Grupė nežinoma. Apie klaidą praneškina administratoriui.',
         'groupID.numeric' => 'Kažkur įvyko klaida identifikuojant grupę. Apie klaidą praneškite administratoriui.',
         'groupID.exists' => 'Nepavyko identifikuoti grupės, į kurią atkuriamas įrankis.',
         'id.required' => 'Įrankis nežinoma. Apie klaidą praneškina administratoriui.',
         'id.numeric' => 'Kažkur įvyko klaida identifikuojant įranki. Apie klaidą praneškite administratoriui.',
         'id.exists' => 'Nepavyko identifikuoti įrankio.'
       ];
     }
    }
