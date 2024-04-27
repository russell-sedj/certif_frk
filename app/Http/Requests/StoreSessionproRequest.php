<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionproRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'date_debut'=>['required','date',],
            'date_fin'=>['required','date','after_or_equal:date_debut'],
            'heure_debut'=>['required','date_format:H:i'],
            'heure_fin'=>['required','date_format:H:i','after:heure_debut'],
            'lieu_id'=>'required'
        ];
    }
}
