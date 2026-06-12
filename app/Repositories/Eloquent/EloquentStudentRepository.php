<?php

namespace App\Repositories\Eloquent;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;

class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function all(array $filters = [])
    {
        return Student::with('user')
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['filiere_id']), fn($q) => $q->where('filiere_id', $filters['filiere_id']))
            ->when(isset($filters['classe_id']), fn($q) => $q->where('classe_id', $filters['classe_id']))
            ->latest()
            ->paginate(min(max((int) ($filters['per_page'] ?? 20), 1), 100));
    }

    public function findPending(array $filters = [])
    {
        $filters['status'] = 'pending';
        return $this->all($filters);
    }

    public function find(int $id)
    {
        return Student::with('user')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Student::create($data);
    }

    public function update(int $id, array $data)
    {
        $student = $this->find($id);
        $student->update($data);
        return $student;
    }

    public function delete(int $id)
    {
        $student = $this->find($id);
        return $student->delete();
    }
}
