<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\StoreNoteRequest;
use App\Models\Note;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * List notes.
     * Admin: all. Teacher: own modules. Student: own notes.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notes = Note::with(['student.user', 'module'])
            ->when($user->isTeacher(), function ($q) use ($user) {
                $q->whereHas('module', function ($q2) use ($user) {
                    $q2->where('teacher_id', $user->teacher->id);
                });
            })
            ->when($user->isStudent(), function ($q) use ($user) {
                $q->where('student_id', $user->student->id)
                  ->where('status', 'validated'); // Students only see validated notes
            })
            ->when($request->module_id, fn($q) => $q->where('module_id', $request->module_id))
            ->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))
            ->latest()
            ->paginate($request->get('per_page', 20));

        return response()->json($notes);
    }

    /**
     * Submit a note (Teacher).
     */
    public function store(StoreNoteRequest $request): JsonResponse
    {
        $user = $request->user();
        $module = Module::findOrFail($request->module_id);

        if ($user->isTeacher() && $module->teacher_id !== $user->teacher->id) {
            return response()->json(['message' => 'You do not teach this module.'], 403);
        }

        $note = Note::updateOrCreate(
            ['student_id' => $request->student_id, 'module_id' => $request->module_id],
            [
                'cc_note'      => $request->cc_note,
                'exam_note'    => $request->exam_note,
                'comment'      => $request->comment,
                'status'       => 'pending', // Always resets to pending when updated
                'submitted_by' => $user->id,
            ]
        );

        return response()->json([
            'message' => 'Note submitted successfully. Pending admin validation.',
            'note'    => $note,
        ]);
    }

    /**
     * Validate a note (Admin).
     */
    public function validateNote(Request $request, Note $note): JsonResponse
    {
        if ($note->status === 'validated') {
            return response()->json(['message' => 'Note is already validated.'], 422);
        }

        $note->update([
            'average'      => $note->computeAverage(),
            'status'       => 'validated',
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Note validated successfully.',
            'note'    => $note,
        ]);
    }
}
