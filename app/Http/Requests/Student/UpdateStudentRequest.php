<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user' => ['sometimes', 'array'],
            'user.first_name' => ['sometimes', 'string', 'max:255'],
            'user.last_name' => ['sometimes', 'string', 'max:255'],
            'user.phone' => ['sometimes', 'string', 'max:255'],
            'filiere_id' => ['sometimes', 'exists:filieres,id'],
            'classe_id' => ['sometimes', 'exists:classes,id'],
            'date_of_birth' => ['sometimes', 'date'],
            'place_of_birth' => ['sometimes', 'string', 'max:255'],
            'gender' => ['sometimes', 'in:M,F'],
            'nationality' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string'],
            'guardian_name' => ['sometimes', 'string', 'max:255'],
            'guardian_phone' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
