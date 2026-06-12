<?php

namespace App\Services;

use App\Models\Student;

class MatriculeService
{
    /**
     * Generate a unique matricule for a validated student.
     *
     * Format: EEMCI-{YEAR}-{FILIERE_CODE}-{SEQUENCE:04d}
     * Example: EEMCI-2024-INFO-0001
     */
    public function generate(Student $student): string
    {
        $year        = date('Y');
        $filiereCode = strtoupper($student->filiere?->code ?? 'ETU');

        // Count validated students in same filiere this year to build sequence
        $count = Student::where('status', 'validated')
            ->where('filiere_id', $student->filiere_id)
            ->whereYear('validated_at', $year)
            ->count();

        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $matricule = "EEMCI-{$year}-{$filiereCode}-{$sequence}";

        // Ensure uniqueness (edge case: concurrent validations)
        while (Student::where('matricule', $matricule)->exists()) {
            $count++;
            $sequence  = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            $matricule = "EEMCI-{$year}-{$filiereCode}-{$sequence}";
        }

        return $matricule;
    }
}
