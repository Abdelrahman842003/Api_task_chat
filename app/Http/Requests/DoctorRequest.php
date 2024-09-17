<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'specialization' => 'required|string',
            'department' => 'required|string',
            'years_of_experience' => 'required|integer',
            'university' => 'required|string',
            'cv' => 'required|string',
            'phone_number' => 'required|string',
            'identification_number' => 'required|string|unique:doctors',
            'address' => 'required|string',
            'age' => 'required|integer',
        ];
    }
}
