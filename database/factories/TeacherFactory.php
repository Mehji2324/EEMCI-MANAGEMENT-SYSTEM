<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(function () {
                $role = Role::firstOrCreate(['name' => 'teacher'], ['display_name' => 'Teacher']);
                return ['role_id' => $role->id];
            }),
            'employee_id' => $this->faker->unique()->numerify('EMP-####'),
            'specialty' => $this->faker->word(),
            'grade' => $this->faker->randomElement(['assistant', 'maître assistant', 'professeur']),
            'department' => $this->faker->word(),
        ];
    }
}
