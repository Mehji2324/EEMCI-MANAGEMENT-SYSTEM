<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_teachers()
    {
        $this->createTeacher();

        $response = $this->actingAsAdmin()->getJson('/api/teachers');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.data');
    }

    public function test_student_cannot_list_teachers()
    {
        $response = $this->actingAsStudent()->getJson('/api/teachers');
        $response->assertStatus(403);
    }
}
