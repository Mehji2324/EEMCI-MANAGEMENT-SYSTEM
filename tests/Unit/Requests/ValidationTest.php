<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    public function test_register_request_validation_rules()
    {
        $request = new RegisterRequest();
        $rules = $request->rules();

        $validator = Validator::make([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'filiere_id' => 1,
            'classe_id' => 1,
        ], $rules);

        $this->assertTrue($validator->passes());
    }

    public function test_register_request_fails_without_required_fields()
    {
        $request = new RegisterRequest();
        $rules = $request->rules();

        $validator = Validator::make([], $rules);

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('first_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }
}
