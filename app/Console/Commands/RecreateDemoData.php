<?php

namespace App\Console\Commands;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RecreateDemoData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'demo:recreate {--wipe-users : Also wipe and reseed users}';

    /**
     * The console command description.
     */
    protected $description = 'Recreate deterministic demo data without Faker (users optional), projects, and tasks.';

    public function handle(): int
    {
        Cache::put('demo_reset_in_progress', true);

        $this->info('Recreating demo data...');

        Schema::disableForeignKeyConstraints();

        $wipeUsers = (bool) $this->option('wipe-users');

        // Delete existing data in order
        Task::query()->delete();
        Project::query()->delete();
        User::query()->whereNot('email', 'advanced@kanban.com')->delete();

        if ($wipeUsers) {
            User::query()->delete();
            $this->components->info('Users wiped.');
        }

        Schema::enableForeignKeyConstraints();

        // Ensure users exist (deterministic, no Faker)
        if (User::query()->count() === 0) {
            $this->seedUsers();
        }

        // Create deterministic projects
        $projects = $this->seedProjects();

        // Create deterministic tasks per project and status
        $this->seedTasks($projects);

        $this->components->twoColumnDetail('Demo data recreation', '<info>DONE</info>');
        Cache::forget('demo_reset_in_progress');
        return self::SUCCESS;
    }

    protected function seedUsers(): void
    {
        $this->components->task('Seeding users (deterministic)', function (): void {
            $users = [
                ['name' => 'Kanban Admin', 'email' => 'advanced@kanban.com', 'password' => 'demo.advancedkanban!2025'],
                ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'password' => 'password'],
                ['name' => 'Bob Smith', 'email' => 'bob@example.com', 'password' => 'password'],
                ['name' => 'Carol Lee', 'email' => 'carol@example.com', 'password' => 'password'],
                ['name' => 'Dan Patel', 'email' => 'dan@example.com', 'password' => 'password'],
                ['name' => 'Eve Torres', 'email' => 'eve@example.com', 'password' => 'password'],
            ];

            foreach ($users as $userData) {
                User::query()->create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                ]);
            }
        });
    }

    /**
     * @return \Illuminate\Support\Collection<int, Project>
     */
    protected function seedProjects(): Collection
    {
        $projects = collect();

        $this->components->task('Seeding projects (deterministic)', function () use (&$projects): void {
            $projectNames = [
                'Project Alpha',
                'Project Beta',
                'Project Gamma',
                'Project Delta',
                'Project Epsilon',
                'Project Zeta',
            ];

            $allUsers = User::query()->orderBy('id')->get(['id']);

            foreach ($projectNames as $i => $name) {
                $ownerId = $allUsers[$i % $allUsers->count()]->id;

                $projects->push(Project::query()->create([
                    'name' => $name,
                    'description' => "Demo project {$name} with deterministic content.",
                    'owner_id' => $ownerId,
                ]));
            }
        });

        return $projects;
    }

    protected function seedTasks(Collection $projects): void
    {
        $this->components->task('Seeding tasks (deterministic)', function () use ($projects): void {
            $statuses = [
                TaskStatus::PENDING,
                TaskStatus::IN_PROGRESS,
                TaskStatus::REVIEW,
                TaskStatus::COMPLETED,
                TaskStatus::ARCHIVED,
            ];

            $priorityCycle = [
                Priority::MEDIUM,
                Priority::HIGH,
                Priority::HIGH,
                Priority::MEDIUM,
                Priority::LOW,
            ];

            $verbs = ['Implement', 'Fix', 'Design', 'Refactor', 'Document', 'Integrate', 'Research', 'Review'];
            $areas = ['authentication', 'API endpoint', 'database schema', 'UI components', 'notifications', 'webhooks', 'background jobs', 'validation rules', 'error handling', 'access control'];

            $users = User::query()->orderBy('id')->pluck('id')->all();
            $userCount = count($users);

            foreach ($projects as $projectIndex => $project) {
                foreach ($statuses as $statusIndex => $status) {
                    for ($i = 0; $i < 5; $i++) {
                        $globalIndex = ($projectIndex * 100) + ($statusIndex * 10) + $i;

                        // Title based on deterministic combination
                        $titleVerb = $verbs[$globalIndex % count($verbs)];
                        $titleArea = $areas[$globalIndex % count($areas)];
                        $title = $titleVerb . ' ' . $titleArea;

                        // Priority pattern similar to original
                        $priority = match ($status) {
                            TaskStatus::PENDING, TaskStatus::IN_PROGRESS => $priorityCycle[$globalIndex % count($priorityCycle)],
                            TaskStatus::REVIEW => [Priority::MEDIUM, Priority::HIGH, Priority::MEDIUM, Priority::LOW][$globalIndex % 4],
                            TaskStatus::COMPLETED => [Priority::MEDIUM, Priority::LOW, Priority::MEDIUM][$globalIndex % 3],
                            TaskStatus::ARCHIVED => [Priority::LOW, Priority::MEDIUM, Priority::LOW][$globalIndex % 3],
                        };

                        // Due date window similar to original but deterministic
                        $base = Carbon::today();
                        if (in_array($status, [TaskStatus::PENDING, TaskStatus::IN_PROGRESS, TaskStatus::REVIEW], true)) {
                            $dueDate = $base->copy()->addDays(3 * ($globalIndex % 20)); // up to ~60 days ahead
                        } else {
                            $dueDate = $base->copy()->subDays(3 * ($globalIndex % 20)); // up to ~60 days back
                        }

                        // Assigned to pattern (no randomness). Archived ~50% unassigned.
                        $assignedTo = null;
                        if ($userCount > 0) {
                            $assignCondition = $status === TaskStatus::ARCHIVED ? ($globalIndex % 2 === 0) : ($globalIndex % 7 !== 0);
                            if ($assignCondition) {
                                $assignedTo = $users[$globalIndex % $userCount];
                            }
                        }

                        $description = 'Task for ' . $project->name . ' focusing on ' . $titleArea . '. '
                            . 'Status: ' . $status->value . ', Priority: ' . $priority->value . '.';

                        Task::query()->create([
                            'project_id' => $project->id,
                            'status' => $status->value,
                            'priority' => $priority->value,
                            'due_date' => $dueDate->format('Y-m-d'),
                            'assigned_to' => $assignedTo,
                            'title' => $title,
                            'description' => $description,
                        ]);
                    }
                }
            }
        });
    }
}


