<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'in_progress', 'review', 'completed', 'archived']);

        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->optional()->paragraphs(2, true),
            'status' => $status,
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+3 months')?->format('Y-m-d'),
            'project_id' => Project::factory(),
            'assigned_to' => $this->faker->optional()->boolean(80) ? User::factory() : null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
