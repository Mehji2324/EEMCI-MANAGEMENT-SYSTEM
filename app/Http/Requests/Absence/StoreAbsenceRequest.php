<?php

namespace App\Http\Requests\Absence;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsenceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id'           => ['required', 'exists:students,id'],
            'module_id'            => ['required', 'exists:modules,id'],
            'date'                 => ['required', 'date'],
            'session'              => ['nullable', 'in:morning,afternoon,full-day'],
            'justified'            => ['boolean'],
            'justification_reason' => ['nullable', 'string', 'max:1000'],
            'justification_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }
}
