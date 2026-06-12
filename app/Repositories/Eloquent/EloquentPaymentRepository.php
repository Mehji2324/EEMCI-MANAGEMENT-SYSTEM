<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function all(array $filters = [])
    {
        $user = $filters['user'] ?? null;
        
        return Payment::with(['student.user', 'createdBy', 'validatedBy'])
            ->when($user && $user->isStudent(), function ($q) use ($user) {
                $q->where('student_id', $user->student->id);
            })
            ->when(isset($filters['student_id']), fn($q) => $q->where('student_id', $filters['student_id']))
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->latest()
            ->paginate(min(max((int) ($filters['per_page'] ?? 20), 1), 100));
    }

    public function find(int $id)
    {
        return Payment::with(['student.user', 'createdBy', 'validatedBy'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function update(int $id, array $data)
    {
        $payment = $this->find($id);
        $payment->update($data);
        return $payment;
    }

    public function delete(int $id)
    {
        $payment = $this->find($id);
        return $payment->delete();
    }
}
