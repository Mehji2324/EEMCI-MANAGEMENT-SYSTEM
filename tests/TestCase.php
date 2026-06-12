<?php

namespace Tests;

use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles for tests
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'teacher'], ['display_name' => 'Teacher']);
        Role::firstOrCreate(['name' => 'student'], ['display_name' => 'Student']);
    }

    protected function createAdmin(array $attributes = []): User
    {
        $role = Role::where('name', 'admin')->first();
        return User::factory()->create(array_merge(['role_id' => $role->id], $attributes));
    }

    protected function createTeacher(array $attributes = []): Teacher
    {
        return Teacher::factory()->create($attributes);
    }

    protected function createStudent(array $attributes = []): Student
    {
        return Student::factory()->create($attributes);
    }

    protected function actingAsAdmin(): self
    {
        return $this->actingAs($this->createAdmin());
    }

    protected function actingAsTeacher(): self
    {
        $teacher = $this->createTeacher();
        return $this->actingAs($teacher->user);
    }

    protected function actingAsStudent(string $status = 'validated'): self
    {
        $student = $this->createStudent(['status' => $status]);
        return $this->actingAs($student->user);
    }
}
