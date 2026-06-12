<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * List all teachers. Admin only.
     */
    public function index(Request $request): JsonResponse
    {
        $teachers = Teacher::with('user')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', function ($q2) use ($request) {
                    $q2->where('first_name', 'like', "%{$request->search}%")
                       ->orWhere('last_name',  'like', "%{$request->search}%")
                       ->orWhere('email',      'like', "%{$request->search}%");
                })->orWhere('employee_id', 'like', "%{$request->search}%")
                  ->orWhere('specialty', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json($teachers);
    }

    /**
     * Create a new teacher. Admin only.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'password'    => ['required', 'string', 'min:8'],
            'employee_id' => ['nullable', 'string', 'unique:teachers,employee_id'],
            'specialty'   => ['nullable', 'string'],
            'grade'       => ['nullable', 'string'],
            'department'  => ['nullable', 'string'],
        ]);

        $teacherRole = Role::where('name', 'teacher')->firstOrFail();

        $user = User::create([
            'role_id'              => $teacherRole->id,
            'first_name'           => $request->first_name,
            'last_name'            => $request->last_name,
            'email'                => $request->email,
            'phone'                => $request->phone,
            'password'             => Hash::make($request->password),
            'must_change_password' => true,
        ]);

        $teacher = Teacher::create([
            'user_id'     => $user->id,
            'employee_id' => $request->employee_id,
            'specialty'   => $request->specialty,
            'grade'       => $request->grade,
            'department'  => $request->department,
        ]);

        return response()->json([
            'message' => 'Teacher created successfully.',
            'teacher' => $teacher->load('user'),
        ], 201);
    }

    /**
     * Show a single teacher.
     * Admin: any teacher. Teacher: own profile.
     */
    public function show(Request $request, Teacher $teacher): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->isTeacher() && $authUser->teacher?->id !== $teacher->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json(
            $teacher->load(['user', 'modules'])
        );
    }

    /**
     * Update a teacher. Admin only.
     */
    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => ['sometimes', 'string', 'unique:teachers,employee_id,'.$teacher->id],
            'specialty'   => ['sometimes', 'string'],
            'grade'       => ['sometimes', 'string'],
            'department'  => ['sometimes', 'string'],
            'first_name'  => ['sometimes', 'string'],
            'last_name'   => ['sometimes', 'string'],
            'phone'       => ['sometimes', 'string'],
        ]);

        $userFields    = array_intersect_key($data, array_flip(['first_name', 'last_name', 'phone']));
        $teacherFields = array_diff_key($data, $userFields);

        if (! empty($userFields)) {
            $teacher->user->update($userFields);
        }
        if (! empty($teacherFields)) {
            $teacher->update($teacherFields);
        }

        return response()->json([
            'message' => 'Teacher updated successfully.',
            'teacher' => $teacher->fresh()->load('user'),
        ]);
    }
}
