<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'module_id' => Module::factory(),
            'cc_note' => $this->faker->randomFloat(2, 0, 20),
            'exam_note' => $this->faker->randomFloat(2, 0, 20),
            'status' => 'pending',
            'comment' => $this->faker->sentence(),
        ];
    }
}
