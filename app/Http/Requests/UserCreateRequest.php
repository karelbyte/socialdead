<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'full_names' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'sex_id' => ['required'],
            'birthdate' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'full_names.required' => 'El nombre completo es requerido.',
            'full_names.string' => 'El nombre tiene que ser válido.',
            'full_names.max' => 'La logitud maxima es de 190 caracteres.',
            'email.required' => 'El correo electronico es requerido.',
            'email.email' => 'No es un correo válido.',
            'email.max' => 'La logitud maxima es de 190 caracteres.',
            'email.unique' => 'El correo ya esta registrado en nuestra red.',
            'password.required' => 'El password es requerido.',
            'password.min' => 'La longitud minima es de 8.',
            'password.confirmed' => 'Password sin confirmar.',
            'sex_id.required' => 'El sexo es requerido',
            'required.required' => 'El fecha de nacimiento es requerida',
        ];
    }
}
