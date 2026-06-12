<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentValidationController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {}

    public function pending(Request $request): JsonResponse
    {
        return response()->json($this->studentService->getPending());
    }

    public function validate(Request $request, Student $student): JsonResponse
    {
        try {
            $result = $this->studentService->validateStudent($student->id, $request->user());

            return response()->json([
                'message' => 'Student validated successfully. Credentials generated.',
                'student' => $result['student'],
                'credentials' => $result['credentials'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function reject(Request $request, Student $student): JsonResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'min:10']
        ]);

        $this->studentService->rejectStudent($student->id, $request->user(), $request->reason);

        return response()->json([
            'message' => 'Student registration rejected.',
            'student' => $student->fresh()
        ]);
    }
}
