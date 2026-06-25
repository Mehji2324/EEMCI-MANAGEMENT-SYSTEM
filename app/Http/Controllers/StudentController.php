<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'filiere_id', 'classe_id']);
        $filters['per_page'] = $request->get('per_page', 20);
        
        return response()->json($this->studentService->getAll($filters));
    }

    public function show(Student $student): JsonResponse
    {
        return response()->json($student->load(['user', 'filiere', 'classe']));
    }

    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        DB::transaction(function () use ($request, $student) {
            if ($request->has('user')) {
                $student->user->update($request->user);
            }
            $student->update($request->except('user'));
        });

        return response()->json([
            'message' => 'Student updated successfully.',
            'student' => $student->fresh(['user'])
        ]);
    }

    public function destroy(Student $student): JsonResponse
    {
        DB::transaction(function () use ($student) {
            $student->user->delete();
            $student->delete();
        });

        return response()->json(['message' => 'Student deleted successfully.']);
    }

    public function updateOwnProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->isStudent()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $request->validate([
            'phone'          => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string', 'max:255'],
            'guardian_name'  => ['nullable', 'string', 'max:100'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($request, $user) {
            if ($request->has('phone')) {
                $user->update(['phone' => $request->phone]);
            }
            $user->student?->update($request->only([
                'address', 'guardian_name', 'guardian_phone'
            ]));
        });

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user'    => $user->fresh(['student', 'role']),
        ]);
    }
}
