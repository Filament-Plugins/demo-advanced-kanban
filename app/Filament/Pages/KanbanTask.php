<?php

namespace App\Filament\Pages;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Models\Task;
use Asmit\AdvancedKanban\Actions\ActionGroup;
use Asmit\AdvancedKanban\Actions\CreateAction;
use Asmit\AdvancedKanban\Columns\KanbanColumn;
use Asmit\AdvancedKanban\Kanban;
use Asmit\AdvancedKanban\Pages\KanbanPage;
use Asmit\AdvancedKanban\RecordAction\DeleteAction;
use Asmit\AdvancedKanban\RecordAction\EditAction;
use Asmit\AdvancedKanban\RecordAction\MoveToTopAction;
use Asmit\AdvancedKanban\RecordAction\ReplicateAction;
use Asmit\AdvancedKanban\Support\RecordPosition;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class KanbanTask extends KanbanPage
{
    protected ?string $heading = 'Task Kanban';

    protected string $view = 'filament.pages.kanban-task';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string $columnHeaderComponent = 'kanban.task-resource.column-header';

    protected static string $cardComponent = 'kanban.task-resource.card';

    protected static ?string $navigationLabel = 'Tasks';

    protected static bool $shouldPersistFilterInSession = true;

    protected static bool $shouldPersistSearchInSession = true;

    /**
     * The status attribute is cast to the TaskStatus enum, but record actions such as
     * MoveToTopAction read it via getAttribute() and pass it straight through to this
     * method, which the base class types as a plain string.
     */
    public function moveRecord(string|int $recordId, string|BackedEnum $newStatus, string|int|null $previousId = null, string|int|null $nextId = null, ?array $transitionData = null): void
    {
        parent::moveRecord(
            $recordId,
            $newStatus instanceof BackedEnum ? (string) $newStatus->value : $newStatus,
            $previousId,
            $nextId,
            $transitionData,
        );
    }

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Tasks',
            '' => 'Kanban',
        ];
    }

    /**
     * Status is already the thing every column sorts by, so a status tab is a filter on
     * information already on screen. These cut across columns instead — "what needs my
     * attention" rather than "where does it currently sit."
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All work')
                ->icon(Heroicon::OutlinedSquares2x2)
                ->badge(fn (): int => $this->countTasks(fn (Builder $query) => $query)),

            'mine' => Tab::make('Assigned to me')
                ->icon(Heroicon::OutlinedUserCircle)
                ->badge(fn (): int => $this->countTasks($this->assignedToMe(...)))
                ->modifyQueryUsing($this->assignedToMe(...)),

            'overdue' => Tab::make('Overdue')
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->badgeColor('danger')
                ->badge(fn (): int => $this->countTasks($this->overdue(...)))
                ->modifyQueryUsing($this->overdue(...)),

            'week' => Tab::make('Due this week')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->badge(fn (): int => $this->countTasks($this->dueThisWeek(...)))
                ->modifyQueryUsing($this->dueThisWeek(...)),
        ];
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    protected function assignedToMe(Builder $query): Builder
    {
        return $query->where('assigned_to', auth()->id());
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    protected function overdue(Builder $query): Builder
    {
        return $query
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now())
            ->whereNotIn('status', [TaskStatus::COMPLETED, TaskStatus::ARCHIVED]);
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    protected function dueThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('due_date', [now()->startOfDay(), now()->addWeek()]);
    }

    /**
     * Tab badges count the whole board, not just the paginated head of each column.
     *
     * @param  \Closure(Builder<Task>): Builder<Task>  $scope
     */
    protected function countTasks(\Closure $scope): int
    {
        return $scope(Task::query())->count();
    }

    public function kanban(Kanban $kanban): Kanban
    {
        return $kanban
            ->model(Task::class)
            ->statusField('status')
            ->orderField('position')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['assignedTo'])->withCount('comments'))
            ->searchableFields(['title', 'description'])
            ->enableLoadingIndicator()
            ->enableFilterIndicator()
            ->columns([
                KanbanColumn::make('pending')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedClock)
                    ->iconColor('gray')
                    ->extraColumnHeadingClass(['kanban-accent-pending'])
                    ->allowedTransitions(['in_progress', 'archived']),

                KanbanColumn::make('in_progress')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->iconColor('info')
                    ->extraColumnHeadingClass(['kanban-accent-in-progress'])
                    ->allowedTransitions(['review', 'pending', 'archived']),

                KanbanColumn::make('review')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedEye)
                    ->iconColor('warning')
                    ->extraColumnHeadingClass(['kanban-accent-review'])
                    ->allowedTransitions(['completed', 'in_progress', 'archived']),

                KanbanColumn::make('completed')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->iconColor('success')
                    ->extraColumnHeadingClass(['kanban-accent-completed'])
                    ->allowedTransitions(['archived', 'pending'])
                    ->requiresFormOnEnter([
                        Textarea::make('completion_note')
                            ->label('Completion note')
                            ->placeholder('What was done to complete this task?')
                            ->required()
                            ->rows(3),
                    ])
                    ->transitionModalHeading('Mark task as completed')
                    ->transitionModalDescription('Add a short note before this card moves into Completed.')
                    ->transitionModalSubmitActionLabel('Complete task')
                    ->saveTransitionDataUsing(function (Task $record, array $data): void {
                        $record->description = trim($record->description."\n\n✔ ".$data['completion_note']);
                    }),

                KanbanColumn::make('archived')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->iconColor('gray')
                    ->extraColumnHeadingClass(['kanban-accent-archived'])
                    ->allowedTransitions(['pending']),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make('edit')
                        ->schema(fn () => $this->taskForm(null)),
                    MoveToTopAction::make(),
                    ReplicateAction::make('replicate')
                        ->icon(Heroicon::OutlinedDocumentDuplicate),
                    DeleteAction::make('delete')
                        ->icon(Heroicon::OutlinedTrash)
                        ->requiresConfirmation()
                        ->color('danger'),
                ]),

                // Left out of the corner menu on purpose: it lives inline in the card footer,
                // next to the count it explains, rather than a click away in a dropdown.
                Action::make('comment')
                    ->label('Comment')
                    ->icon(Heroicon::OutlinedChatBubbleLeftEllipsis)
                    ->iconButton()
                    ->color('gray')
                    ->size(Size::ExtraSmall)
                    ->record(fn (array $arguments): ?Task => filled($arguments['record'] ?? null)
                        ? Task::query()->find($arguments['record'])
                        : null)
                    ->modalWidth(Width::Medium)
                    ->modalHeading(fn (Task $record): string => "Comment on \"{$record->title}\"")
                    ->modalSubmitActionLabel('Post')
                    ->schema([
                        Textarea::make('body')
                            ->hiddenLabel()
                            ->placeholder('Leave a note on this card…')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (array $data, Task $record): void {
                        $record->comments()->create([
                            'user_id' => auth()->id(),
                            'body' => $data['body'],
                        ]);

                        Notification::make()
                            ->title('Comment posted')
                            ->body($record->title)
                            ->success()
                            ->send();

                        $this->loadKanbanRecords();
                    }),
            ])
            ->columnHeaderActions([
                CreateAction::make()
                    ->schema(function (array $arguments): array {
                        return $this->taskForm($arguments['status']);
                    })
                    ->icon(Heroicon::OutlinedPlus)
                    ->hiddenLabel()
                    ->link(),

                ActionGroup::make([
                    Action::make('columnSummary')
                        ->label('Column summary')
                        ->icon(Heroicon::OutlinedChartBar)
                        ->modalHeading(fn (array $arguments): string => $this->columnLabel($arguments['status'] ?? '').' at a glance')
                        ->modalWidth(Width::Medium)
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close')
                        ->modalContent(fn (array $arguments) => view('filament.kanban.column-summary', [
                            'summary' => $this->columnSummary($arguments['status'] ?? ''),
                        ])),

                    Action::make('sortByDueDate')
                        ->label('Sort by due date')
                        ->icon(Heroicon::OutlinedArrowsUpDown)
                        ->requiresConfirmation()
                        ->modalDescription('Renumbers this column so the soonest due date sits at the top.')
                        ->action($this->sortColumnByDueDate(...)),
                ])
                    ->label('Column actions')
                    ->icon(Heroicon::OutlinedEllipsisHorizontal)
                    ->size(Size::Small),
            ])
            ->filterFormSchema([
                Select::make('project_id')
                    ->model(Task::class)
                    ->relationship('project', 'name')
                    ->preload()
                    ->searchable()
                    ->nullable(),

                Select::make('status')
                    ->options(TaskStatus::class)
                    ->default([TaskStatus::PENDING, TaskStatus::IN_PROGRESS, TaskStatus::REVIEW, TaskStatus::COMPLETED, TaskStatus::ARCHIVED])
                    ->multiple()
                    ->nullable(),

                Select::make('priority')
                    ->options(Priority::class)
                    ->default([Priority::MEDIUM, Priority::HIGH])
                    ->multiple()
                    ->nullable(),
            ])
            ->applyFiltersUsing(function (Builder $query, array $filters): Builder {
                if (! empty($filters['project_id'])) {
                    $query->where('project_id', $filters['project_id']);
                }
                if (! empty($filters['status'])) {
                    $query->whereIn('status', $filters['status']);
                }
                if (! empty($filters['priority'])) {
                    $query->whereIn('priority', $filters['priority']);
                }

                return $query;
            });
    }

    protected function columnLabel(string $status): string
    {
        $columns = $this->getKanban()->getCachedMountedKanbanColumns();

        return ($columns[$status] ?? null)?->getLabel() ?? $status;
    }

    /**
     * @return array{total: int, overdue: int, unassigned: int, priorities: array<string, int>}
     */
    protected function columnSummary(string $status): array
    {
        $tasks = $this->getKanban()->getQuery()->where('status', $status)->get();

        return [
            'total' => $tasks->count(),
            'overdue' => $tasks->filter(fn (Task $task): bool => $task->due_date
                && $task->due_date->isPast()
                && ! in_array($task->status, [TaskStatus::COMPLETED, TaskStatus::ARCHIVED], true))->count(),
            'unassigned' => $tasks->whereNull('assigned_to')->count(),
            'priorities' => collect(Priority::cases())
                ->mapWithKeys(fn (Priority $priority): array => [
                    $priority->value => $tasks->where('priority', $priority)->count(),
                ])
                ->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    protected function sortColumnByDueDate(array $arguments): void
    {
        $status = $arguments['status'] ?? null;

        if (blank($status)) {
            return;
        }

        Task::query()
            ->where('status', $status)
            ->orderByRaw('due_date is null, due_date')
            ->get()
            ->each(fn (Task $task, int $index) => $task->update([
                'position' => ($index + 1) * RecordPosition::GAP,
            ]));

        $this->loadKanbanRecords();

        Notification::make()
            ->title($this->columnLabel($status).' sorted by due date')
            ->success()
            ->send();
    }

    public function taskForm($status): array
    {
        return [
            Select::make('status')
                ->options(TaskStatus::class)
                ->default($status),

            Select::make('project_id')
                ->required()
                ->searchable()
                ->relationship('project', 'name'),

            TextInput::make('title')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),

            Select::make('assigned_to')
                ->searchable()
                ->relationship('assignedTo', 'name')
                ->nullable(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('Task List')
                ->url(ListTasks::getUrl())
                ->icon(Heroicon::ListBullet),
        ];
    }

    public function viewAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('view')
            ->label('Task Details')
            ->slideOver()
            ->record(fn (array $arguments) => Task::query()->with(['project', 'assignedTo'])->find($arguments['recordId']))
            ->modalSubmitAction(false)
            ->schema(function ($record) {
                return [
                    TextEntry::make('Title')
                        ->default($record->title),

                    TextEntry::make('Description')
                        ->default($record->description),

                    TextEntry::make('status')
                        ->badge()
                        ->icon($record->status->getIcon())
                        ->color($record->status->getColor())
                        ->default($record->status->getLabel()),

                    TextEntry::make('priority')
                        ->badge()
                        ->icon($record->priority->getIcon())
                        ->color($record->priority->getColor())
                        ->default($record->priority->getLabel()),

                    TextEntry::make('Project')
                        ->default($record->project->name),

                    TextEntry::make('Assigned To')
                        ->badge()
                        ->default($record->assignedTo?->name ?? 'Unassigned'),

                    TextEntry::make('due_date')
                        ->default($record->due_date?->toFormattedDateString() ?? 'No due date'),

                ];
            });
    }
}
