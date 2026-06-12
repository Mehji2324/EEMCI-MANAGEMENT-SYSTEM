<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'module_id' => Module::factory(),
            'date' => $this->faker->date(),
            'session' => $this->faker->randomElement(['morning', 'afternoon', 'full-day']),
            'justified' => false,
            'status' => 'pending',
        ];
    }
}
