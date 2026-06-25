<?php

namespace App\Services;

use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentService
{
    public function __construct(
        protected StudentRepositoryInterface $studentRepo,
        protected MatriculeService $matriculeService,
        protected AcademicEmailService $emailService
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->studentRepo->all($filters);
    }

    public function getPending()
    {
        return $this->studentRepo->findPending();
    }

    public function validateStudent(int $id, User $adminUser)
    {
        return DB::transaction(function () use ($id, $adminUser) {
            $student = $this->studentRepo->find($id);
            
            if ($student->status !== 'pending') {
                throw new \Exception('Student is not pending validation.');
            }

            $matricule = $this->matriculeService->generate($student);
            $academicEmail = $this->emailService->generate($student);
            $tempPassword = Str::random(10);

            $student->user->update([
                'email' => $academicEmail,
                'password' => Hash::make($tempPassword),
                'must_change_password' => true,
            ]);

            $this->studentRepo->update($id, [
                'matricule' => $matricule,
                'academic_email' => $academicEmail,
                'status' => 'validated',
                'validated_by' => $adminUser->id,
                'validated_at' => now(),
            ]);

            return [
                'student' => $student->fresh(['user']),
                'credentials' => [
                    'email' => $academicEmail,
                    'password' => $tempPassword,
                ]
            ];
        });
    }

    public function rejectStudent(int $id, User $adminUser, string $reason)
    {
        return $this->studentRepo->update($id, [
            'status' => 'rejected',
            'validated_by' => $adminUser->id,
            'validated_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }
}
