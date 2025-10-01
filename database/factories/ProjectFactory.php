<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
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
        $start = fake()->optional()->dateTimeBetween('-2 months', 'now');
        $end = $start ? fake()->optional()->dateTimeBetween($start, '+3 months') : null;

        return [
            'name' => fake()->unique()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['Planning', 'Active', 'On Hold', 'Completed']),
            'start_date' => $start ? $start->format('Y-m-d') : null,
            'end_date' => $end ? $end->format('Y-m-d') : null,
            // Prefer overriding owner_id when using the factory; fallback creates a user
            'owner_id' => fn () => \App\Models\User::factory(),
        ];
    }
}
