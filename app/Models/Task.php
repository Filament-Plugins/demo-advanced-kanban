<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'position',
        'due_date',
        'priority',
        'assigned_to',
        'project_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'position' => 'float',
        'priority' => Priority::class,
        'status' => TaskStatus::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Task $task): void {
            if ($task->position !== null) {
                return;
            }

            $task->position = static::query()->where('status', $task->status)->max('position') + 1.0;
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function unassigned(): bool
    {
        return is_null($this->assigned_to);
    }
}
