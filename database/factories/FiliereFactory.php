<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FiliereFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'code' => Str::upper($this->faker->unique()->lexify('???')),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
