<?php

namespace App\Http\Controllers;

use App\Http\Requests\Absence\StoreAbsenceRequest;
use App\Models\Absence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsenceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $absences = Absence::with(['student.user', 'module'])
            ->when($user->isTeacher(), function ($q) use ($user) {
                $q->whereHas('module', function ($q2) use ($user) {
                    $q2->where('teacher_id', $user->teacher->id);
                });
            })
            ->when($user->isStudent(), function ($q) use ($user) {
                $q->where('student_id', $user->student->id);
            })
            ->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))
            ->when($request->module_id, fn($q) => $q->where('module_id', $request->module_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate($request->get('per_page', 20));

        return response()->json($absences);
    }

    public function store(StoreAbsenceRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $data['submitted_by'] = $user->id;
        $data['status']       = 'pending';

        if ($request->hasFile('justification_document')) {
            $path = $request->file('justification_document')->store('absences/justifications', 'public');
            $data['justification_document'] = $path;
            $data['justified'] = true;
        }

        $absence = Absence::create($data);

        return response()->json([
            'message' => 'Absence recorded successfully.',
            'absence' => $absence,
        ], 201);
    }

    public function validateAbsence(Request $request, Absence $absence): JsonResponse
    {
        $request->validate([
            'status'        => ['required', 'in:validated,rejected'],
            'admin_comment' => ['nullable', 'string'],
        ]);

        $absence->update([
            'status'        => $request->status,
            'admin_comment' => $request->admin_comment,
            'validated_by'  => $request->user()->id,
            'validated_at'  => now(),
        ]);

        return response()->json([
            'message' => "Absence {$request->status} successfully.",
            'absence' => $absence,
        ]);
    }

    public function downloadJustification(Absence $absence)
    {
        if (! $absence->justification_document || ! Storage::disk('public')->exists($absence->justification_document)) {
            return response()->json(['message' => 'Document not found.'], 404);
        }

        return Storage::disk('public')->download($absence->justification_document);
    }
}
