<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->paragraph(),
            'start_date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'deadline' => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
        ];
    }
}
