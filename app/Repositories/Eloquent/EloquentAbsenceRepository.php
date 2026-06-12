<?php

namespace App\Repositories\Eloquent;

use App\Models\Absence;
use App\Repositories\Contracts\AbsenceRepositoryInterface;

class EloquentAbsenceRepository implements AbsenceRepositoryInterface
{
    public function all(array $filters = [])
    {
        $user = $filters['user'] ?? null;
        
        return Absence::with(['student.user', 'module'])
            ->when($user && $user->isTeacher(), function ($q) use ($user) {
                $q->whereHas('module', function ($q2) use ($user) {
                    $q2->where('teacher_id', $user->teacher->id);
                });
            })
            ->when($user && $user->isStudent(), function ($q) use ($user) {
                $q->where('student_id', $user->student->id);
            })
            ->when(isset($filters['student_id']), fn($q) => $q->where('student_id', $filters['student_id']))
            ->when(isset($filters['module_id']), fn($q) => $q->where('module_id', $filters['module_id']))
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->latest()
            ->paginate(min(max((int) ($filters['per_page'] ?? 20), 1), 100));
    }

    public function find(int $id)
    {
        return Absence::with(['student.user', 'module'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Absence::create($data);
    }

    public function update(int $id, array $data)
    {
        $absence = $this->find($id);
        $absence->update($data);
        return $absence;
    }
}
