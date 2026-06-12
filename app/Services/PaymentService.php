<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Models\User;

class PaymentService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepo,
        protected PaymentReferenceService $referenceService
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->paymentRepo->all($filters);
    }

    public function createPayment(array $data, User $creator)
    {
        $data['reference']  = $this->referenceService->generate();
        $data['created_by'] = $creator->id;
        $data['status']     = 'pending';

        return $this->paymentRepo->create($data);
    }

    public function updateStatus(int $id, array $data, User $adminUser)
    {
        $payment = $this->paymentRepo->find($id);

        if ($data['status'] === 'paid' && $payment->status !== 'paid') {
            $data['paid_at']      = now();
            $data['validated_by'] = $adminUser->id;
        }

        return $this->paymentRepo->update($id, $data);
    }
}
