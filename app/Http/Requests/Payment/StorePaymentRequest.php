<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id'     => ['required', 'exists:students,id'],
            'type'           => ['required', 'string', 'max:100'],
            'description'    => ['nullable', 'string', 'max:500'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'currency'       => ['nullable', 'string', 'size:3'],
            'payment_method' => ['nullable', 'in:cash,mobile_money,bank_transfer'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
