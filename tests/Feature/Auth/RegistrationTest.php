<?php

namespace Tests\Feature\Auth;

use App\Models\Classe;
use App\Models\Filiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_register()
    {
        $filiere = Filiere::factory()->create();
        $classe = Classe::factory()->create(['filiere_id' => $filiere->id]);

        $response = $this->postJson('/api/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'filiere_id' => $filiere->id,
            'classe_id' => $classe->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => ['id', 'first_name', 'last_name', 'email'],
                         'student' => ['id', 'status']
                     ]
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
        $this->assertDatabaseHas('students', ['status' => 'pending']);
    }
}
