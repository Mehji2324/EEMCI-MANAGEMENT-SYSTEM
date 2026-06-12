<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'module_id'  => ['required', 'exists:modules,id'],
            'cc_note'    => ['nullable', 'numeric', 'min:0', 'max:20'],
            'exam_note'  => ['nullable', 'numeric', 'min:0', 'max:20'],
            'comment'    => ['nullable', 'string', 'max:500'],
        ];
    }
}
