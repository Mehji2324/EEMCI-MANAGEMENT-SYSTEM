<?php

namespace Tests\Unit\Services;

use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentService $studentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->studentService = app(StudentService::class);
    }

    public function test_validate_student_successfully()
    {
        $student = $this->createStudent(['status' => 'pending']);
        $admin = $this->createAdmin();

        $result = $this->studentService->validateStudent($student->id, $admin->id);

        $this->assertTrue($result);
        
        $student->refresh();
        $this->assertEquals('validated', $student->status);
        $this->assertEquals($admin->id, $student->validated_by);
        $this->assertNotNull($student->validated_at);
        $this->assertNotNull($student->matricule);
        $this->assertNotNull($student->academic_email);
        $this->assertNotNull($student->user->password);
    }

    public function test_validate_already_validated_student_throws_exception()
    {
        $student = $this->createStudent(['status' => 'validated']);
        $admin = $this->createAdmin();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Student is already validated or rejected.');

        $this->studentService->validateStudent($student->id, $admin->id);
    }

    public function test_reject_student_successfully()
    {
        $student = $this->createStudent(['status' => 'pending']);
        $admin = $this->createAdmin();

        $result = $this->studentService->rejectStudent($student->id, $admin->id, 'Missing documents');

        $this->assertTrue($result);

        $student->refresh();
        $this->assertEquals('rejected', $student->status);
        $this->assertEquals('Missing documents', $student->rejection_reason);
        $this->assertEquals($admin->id, $student->validated_by);
    }
}
