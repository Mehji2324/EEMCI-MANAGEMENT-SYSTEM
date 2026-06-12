<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'reference' => 'PAY-' . date('Y') . '-' . $this->faker->unique()->numerify('#####'),
            'type' => $this->faker->randomElement(['scolarite', 'inscription', 'examen']),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 10000, 500000),
            'currency' => 'XAF',
            'status' => 'pending',
            'payment_method' => $this->faker->randomElement(['cash', 'mobile_money', 'bank_transfer']),
        ];
    }
}
