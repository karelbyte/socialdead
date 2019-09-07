<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailUpdateRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
        ];
    }

    public function messages()
    {
        return [

            'email.required' => 'El correo electronico es requerido.',
            'email.email' => 'No es un correo válido.',
            'email.max' => 'La logitud maxima es de 190 caracteres.',
            'email.unique' => 'El correo ya esta registrado en nuestra red.',
        ];
    }
}
