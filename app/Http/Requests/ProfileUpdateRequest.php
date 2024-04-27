<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'matricule'=>['required', 'string',
             Rule::unique('users', 'matricule')->where(function ($query){
                if ($this->filled('role')) {
                    return $query->where('role', $this->input('role'));
                } else {
                    return $query->whereIn('role', ['aucun']);
                }
            })->ignore($this->user->id),
               ],
            'surname'=>['required', 'string', 'max:255'],
            'sexe'=>'required',
            'role'=>'string',
            'titre' => ['nullable', 'string', Rule::unique('users', 'titre')->ignore($this->user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user->id)],
        ];
    }
}
