<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExerciceRelation>
 */
class ExerciceRelationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'exercice_id' => $this->faker->numberBetween(1, 10),
            'muscle_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
