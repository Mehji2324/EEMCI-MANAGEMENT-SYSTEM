<?php

namespace App\Repositories\Eloquent;

use App\Models\Note;
use App\Repositories\Contracts\NoteRepositoryInterface;

class EloquentNoteRepository implements NoteRepositoryInterface
{
    public function all(array $filters = [])
    {
        $user = $filters['user'] ?? null;
        
        return Note::with(['student.user', 'module'])
            ->when($user && $user->isTeacher(), function ($q) use ($user) {
                $q->whereHas('module', function ($q2) use ($user) {
                    $q2->where('teacher_id', $user->teacher->id);
                });
            })
            ->when($user && $user->isStudent(), function ($q) use ($user) {
                $q->where('student_id', $user->student->id)
                  ->where('status', 'validated');
            })
            ->when(isset($filters['module_id']), fn($q) => $q->where('module_id', $filters['module_id']))
            ->when(isset($filters['student_id']), fn($q) => $q->where('student_id', $filters['student_id']))
            ->latest()
            ->paginate(min(max((int) ($filters['per_page'] ?? 20), 1), 100));
    }

    public function find(int $id)
    {
        return Note::with(['student.user', 'module'])->findOrFail($id);
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        return Note::updateOrCreate($attributes, $values);
    }

    public function update(int $id, array $data)
    {
        $note = $this->find($id);
        $note->update($data);
        return $note;
    }
}
