<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::first();
        if (! $owner) {
            $owner = User::factory()->create();
        }

        $projects = Project::query()->inRandomOrder()->take(5)->get();
        if ($projects->isEmpty()) {
            $projects = Project::factory()->count(3)->for($owner, 'owner')->create();
        }

        foreach ($projects as $project) {
            Task::factory()->count(rand(3, 10))->create([
                'project_id' => $project->id,
                'created_by' => $owner->id,
            ]);
        }
    }
}
