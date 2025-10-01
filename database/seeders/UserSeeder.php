<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(20)->create()->each(function (User $user) {
            rand(1, 2) % 2 == 0 ? $user->syncRoles('Admin') : $user->syncRoles('Viewer');
        });
    }
}
