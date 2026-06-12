<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_profile()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJsonPath('data.email', $user->email);
    }

    public function test_user_can_logout()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->postJson('/api/auth/logout');

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Logged out successfully']);
    }
}
