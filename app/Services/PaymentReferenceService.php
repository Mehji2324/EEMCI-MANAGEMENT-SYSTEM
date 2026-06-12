<?php

namespace App\Services;

use App\Models\Payment;

class PaymentReferenceService
{
    /**
     * Generate a unique payment reference.
     * Format: PAY-{YEAR}-{SEQUENCE:05d}
     * Example: PAY-2024-00001
     */
    public function generate(): string
    {
        $year  = date('Y');
        $count = Payment::whereYear('created_at', $year)->count();

        $reference = "PAY-{$year}-" . str_pad($count + 1, 5, '0', STR_PAD_LEFT);

        while (Payment::where('reference', $reference)->exists()) {
            $count++;
            $reference = "PAY-{$year}-" . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
        }

        return $reference;
    }
}
