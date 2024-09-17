<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:patients',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string',
            'identification_number' => 'required|string',
            'address' => 'required|string',
            'age' => 'required|integer',
            'medical_history' => 'required|string',
            'allergies' => 'required|string',
            'medications' => 'required|string',
        ];
    }
}
