<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin owner
        $owner = User::factory()->create([
            'email' => 'admin@example.com'
        ]);
        $owner->syncRoles('Admin');

        // Additional members/viewers
        $members = User::factory()->count(3)->create();
        foreach ($members as $member) {
            $member->syncRoles('Viewer');
        }

        // Create projects for owner and attach users
        Project::factory()
            ->count(5)
            ->for($owner, 'owner')
            ->create()
            ->each(function (Project $project) use ($owner, $members) {
                // Attach members to pivot if exists
                try {
                    $project->users()->syncWithoutDetaching($members->pluck('id')->all());
                } catch (\Throwable $e) {
                    // pivot may differ; ignore
                }

                // Tasks under each project
                Task::factory()->count(rand(5, 12))->create([
                    'project_id' => $project->id,
                    'created_by' => $owner->id,
                ]);
            });
    }
}
