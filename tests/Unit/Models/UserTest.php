<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_role_relationship()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals($role->id, $user->role->id);
    }

    public function test_has_role_method()
    {
        $role = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('student'));
    }

    public function test_is_admin_method()
    {
        $user = $this->createAdmin();
        $this->assertTrue($user->isAdmin());
    }

    public function test_is_teacher_method()
    {
        $teacher = $this->createTeacher();
        $this->assertTrue($teacher->user->isTeacher());
    }

    public function test_is_student_method()
    {
        $student = $this->createStudent();
        $this->assertTrue($student->user->isStudent());
    }

    public function test_full_name_accessor()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $user->full_name);
    }
}
