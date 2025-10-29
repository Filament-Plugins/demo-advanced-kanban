<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have users and projects
        if (User::query()->count() === 0) {
            $this->call(UserSeeder::class);
        }

        if (Project::query()->count() === 0) {
            $this->call(ProjectSeeder::class);
        }

        // Create exactly 5 tasks for each status per project with realistic data
        $statuses = ['pending', 'in_progress', 'review', 'completed', 'archived'];

        Project::query()->each(function (Project $project) use ($statuses): void {
            foreach ($statuses as $status) {
                Task::factory()
                    ->count(5)
                    ->state(function () use ($project, $status) {
                        $priority = match ($status) {
                            'pending', 'in_progress' => fake()->randomElement(['medium', 'high', 'high', 'medium', 'low']),
                            'review' => fake()->randomElement(['medium', 'high', 'medium', 'low']),
                            'completed' => fake()->randomElement(['medium', 'low', 'medium']),
                            'archived' => fake()->randomElement(['low', 'medium', 'low']),
                            default => fake()->randomElement(['low', 'medium', 'high']),
                        };

                        $dueDateDateTime = in_array($status, ['pending', 'in_progress', 'review'], true)
                            ? fake()->dateTimeBetween('now', '+2 months')
                            : fake()->dateTimeBetween('-2 months', 'now');
                        $dueDate = $dueDateDateTime?->format('Y-m-d');

                        $assignedTo = fake()->boolean($status === 'archived' ? 50 : 85)
                            ? User::query()->inRandomOrder()->value('id')
                            : null;

                        $verbs = ['Implement', 'Fix', 'Design', 'Refactor', 'Document', 'Integrate', 'Research', 'Review'];
                        $areas = ['authentication', 'API endpoint', 'database schema', 'UI components', 'notifications', 'webhooks', 'background jobs', 'validation rules', 'error handling', 'access control'];
                        $title = sprintf(
                            '%s %s',
                            fake()->randomElement($verbs),
                            fake()->randomElement($areas)
                        );

                        return [
                            'project_id' => $project->id,
                            'status' => $status,
                            'priority' => $priority,
                            'due_date' => $dueDate,
                            'assigned_to' => $assignedTo,
                            'title' => $title,
                            'description' => fake()->paragraphs(2, true),
                        ];
                    })
                    ->create();
            }
        });
    }
}
