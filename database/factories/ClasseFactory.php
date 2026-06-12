<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'filiere_id' => Filiere::factory(),
            'name' => $this->faker->randomElement(['L1', 'L2', 'L3', 'M1', 'M2']),
            'level' => $this->faker->randomElement(['licence', 'master']),
            'academic_year' => '2024-2025',
            'capacity' => 50,
        ];
    }
}
