<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_payment()
    {
        $student = $this->createStudent();

        $response = $this->actingAsAdmin()->postJson('/api/payments', [
            'student_id' => $student->id,
            'type' => 'scolarite',
            'amount' => 50000,
            'payment_method' => 'cash'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', ['student_id' => $student->id, 'amount' => 50000]);
    }

    public function test_student_cannot_record_payment()
    {
        $response = $this->actingAsStudent()->postJson('/api/payments', [
            'student_id' => 1,
            'type' => 'scolarite',
            'amount' => 50000,
            'payment_method' => 'cash'
        ]);

        $response->assertStatus(403);
    }
}
