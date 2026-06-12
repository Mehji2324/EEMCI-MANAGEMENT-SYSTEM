<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],

            // Optional student profile fields
            'date_of_birth'  => ['nullable', 'date'],
            'place_of_birth' => ['nullable', 'string', 'max:150'],
            'gender'         => ['nullable', 'in:M,F'],
            'nationality'    => ['nullable', 'string', 'max:100'],
            'address'        => ['nullable', 'string'],
            'guardian_name'  => ['nullable', 'string', 'max:150'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
