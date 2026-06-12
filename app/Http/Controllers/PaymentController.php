<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\StorePaymentRequest;
use App\Services\PaymentService;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['student_id', 'status']);
        $filters['user'] = $request->user();
        $filters['per_page'] = $request->get('per_page', 20);

        return response()->json($this->paymentService->getAll($filters));
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = $this->paymentService->createPayment(
            $request->validated(), 
            $request->user()
        );

        return response()->json([
            'message' => 'Payment record created successfully.',
            'payment' => $payment,
        ], 201);
    }

    public function show(Request $request, Payment $payment): JsonResponse
    {
        $user = $request->user();

        if ($user->isStudent() && $payment->student_id !== $user->student->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json($payment->load(['student.user', 'createdBy', 'validatedBy']));
    }

    public function updateStatus(Request $request, Payment $payment): JsonResponse
    {
        $request->validate([
            'status'         => ['required', 'in:paid,failed,refunded'],
            'transaction_id' => ['nullable', 'string'],
            'notes'          => ['nullable', 'string'],
        ]);

        $updatedPayment = $this->paymentService->updateStatus(
            $payment->id, 
            $request->only(['status', 'transaction_id', 'notes']), 
            $request->user()
        );

        return response()->json([
            'message' => "Payment marked as {$request->status}.",
            'payment' => $updatedPayment,
        ]);
    }
}
