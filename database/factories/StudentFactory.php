<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(function () {
                $role = Role::firstOrCreate(['name' => 'student'], ['display_name' => 'Student']);
                return ['role_id' => $role->id];
            }),
            'filiere_id' => Filiere::factory(),
            'classe_id' => Classe::factory(),
            'matricule' => $this->faker->unique()->numerify('MAT-####'),
            'academic_email' => $this->faker->unique()->safeEmail(),
            'status' => 'pending',
            'enrollment_year' => '2024-2025',
            'date_of_birth' => $this->faker->date(),
            'place_of_birth' => $this->faker->city(),
            'gender' => $this->faker->randomElement(['M', 'F']),
            'nationality' => $this->faker->country(),
            'address' => $this->faker->address(),
            'guardian_name' => $this->faker->name(),
            'guardian_phone' => $this->faker->phoneNumber(),
        ];
    }
}
