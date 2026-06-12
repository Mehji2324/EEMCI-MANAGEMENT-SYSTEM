<?php

namespace Tests\Feature\API;

use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_submit_note()
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $module = Module::factory()->create(['teacher_id' => $teacher->id]);

        $response = $this->actingAs($teacher->user)->postJson('/api/notes', [
            'student_id' => $student->id,
            'module_id' => $module->id,
            'cc_note' => 15,
            'exam_note' => 14
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('notes', ['student_id' => $student->id, 'module_id' => $module->id, 'status' => 'pending']);
    }
}
