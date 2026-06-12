<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'classe_id' => Classe::factory(),
            'teacher_id' => Teacher::factory(),
            'name' => $this->faker->words(2, true),
            'code' => Str::upper($this->faker->unique()->lexify('???###')),
            'description' => $this->faker->sentence(),
            'credits' => $this->faker->numberBetween(2, 6),
            'semester' => $this->faker->randomElement(['S1', 'S2', 'S3', 'S4']),
            'cc_weight' => 0.40,
            'exam_weight' => 0.60,
            'volume_hours' => 30,
            'is_active' => true,
        ];
    }
}
