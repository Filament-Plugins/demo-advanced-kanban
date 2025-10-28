<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have users to assign as owners
        if (User::query()->count() === 0) {
            $this->call(UserSeeder::class);
        }

        // Create projects with owners
        Project::factory()
            ->count(8)
            ->state(function () {
                return [
                    'owner_id' => User::query()->inRandomOrder()->value('id'),
                ];
            })
            ->create();
    }
}
