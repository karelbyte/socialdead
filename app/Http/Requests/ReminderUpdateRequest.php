<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReminderUpdateRequest extends FormRequest
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
            'reminder_note' => ['required', 'string', 'max:1190'],
            'moment' => ['required', 'date'],
            'token' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'reminder_note.required' => 'Reseña no puede estar en blanco',
            'moment.required' => 'Se requiere una fecha',
        ];
    }
}
