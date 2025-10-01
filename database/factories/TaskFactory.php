<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $due = fake()->optional()->dateTimeBetween('now', '+3 months');

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['Todo', 'In Progress', 'Review', 'Done']),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High', 'Urgent']),
            'due_date' => $due ? $due->format('Y-m-d H:i:s') : null,
            'project_id' => fn () => \App\Models\Project::factory(),
            'created_by' => fn () => \App\Models\User::factory(),
        ];
    }
}
