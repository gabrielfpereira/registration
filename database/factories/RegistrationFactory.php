<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_name' => $this->faker->name(),
            'class_number' => $this->faker->word(),
            'status'       => $this->faker->randomElement(['Pendente', 'Concluído']),
            'type'         => $this->faker->randomElement(['medida', 'infracao', 'suspensao']),
            'user_id'      => 1,
        ];
    }
}
