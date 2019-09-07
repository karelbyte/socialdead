<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordlUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'El password es requerido.',
            'password.min' => 'La longitud minima es de 8.',
            'password.confirmed' => 'Password sin confirmar.',
        ];
    }
}
