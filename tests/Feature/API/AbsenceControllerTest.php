<?php

namespace Tests\Feature\API;

use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_record_absence()
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $module = Module::factory()->create(['teacher_id' => $teacher->id]);

        $response = $this->actingAs($teacher->user)->postJson('/api/absences', [
            'student_id' => $student->id,
            'module_id' => $module->id,
            'date' => '2024-01-01',
            'session' => 'morning'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('absences', ['student_id' => $student->id, 'date' => '2024-01-01']);
    }
}
