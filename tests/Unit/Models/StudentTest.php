<?php

namespace Tests\Unit\Models;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_belongs_to_user()
    {
        $student = $this->createStudent();
        $this->assertInstanceOf(User::class, $student->user);
    }

    public function test_student_belongs_to_filiere()
    {
        $filiere = Filiere::factory()->create();
        $student = $this->createStudent(['filiere_id' => $filiere->id]);
        $this->assertInstanceOf(Filiere::class, $student->filiere);
    }

    public function test_student_belongs_to_classe()
    {
        $classe = Classe::factory()->create();
        $student = $this->createStudent(['classe_id' => $classe->id]);
        $this->assertInstanceOf(Classe::class, $student->classe);
    }

    public function test_pending_scope()
    {
        $this->createStudent(['status' => 'pending']);
        $this->createStudent(['status' => 'validated']);
        
        $pendingStudents = Student::pending()->get();
        
        $this->assertCount(1, $pendingStudents);
        $this->assertEquals('pending', $pendingStudents->first()->status);
    }

    public function test_validated_scope()
    {
        $this->createStudent(['status' => 'pending']);
        $this->createStudent(['status' => 'validated']);
        
        $validatedStudents = Student::validated()->get();
        
        $this->assertCount(1, $validatedStudents);
        $this->assertEquals('validated', $validatedStudents->first()->status);
    }
}
