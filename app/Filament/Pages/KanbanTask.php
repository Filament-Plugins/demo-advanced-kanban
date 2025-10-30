<?php

namespace App\Filament\Pages;

use App\Enums\TaskStatus;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Models\Project;
use App\Models\Task;
use Asmit\AdvancedKanban\Actions\ActionGroup;
use Asmit\AdvancedKanban\Columns\KanbanColumn;
use Asmit\AdvancedKanban\Kanban;
use Asmit\AdvancedKanban\Pages\KanbanPage;
use Asmit\AdvancedKanban\RecordAction\Action;
use Asmit\AdvancedKanban\RecordAction\DeleteAction;
use Asmit\AdvancedKanban\RecordAction\EditAction;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class KanbanTask extends KanbanPage
{
    protected ?string $heading = 'Task Kanban';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string $columnHeaderComponent = 'kanban.task-resource.column-header';

    protected static string $cardComponent = 'kanban.task-resource.card';

    protected static ?string $navigationLabel = 'Tasks';

    protected static bool $shouldPersistFilterInSession = true;

    protected static bool $shouldPersistSearchInSession = true;

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Tasks',
            ''=>'Kanban',
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('All')
            ->icon(Heroicon::OutlinedSquare3Stack3d)
            ,
            Tab::make('pending')
                ->label('Pending')
                ->icon(Heroicon::OutlinedClock)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),

            Tab::make('in_progress')
                ->label('In Progress')
                ->icon(Heroicon::OutlinedArrowPath)
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_progress')),
        ];
    }

    public function kanban(Kanban $kanban): Kanban
    {
        return $kanban
            ->model(Task::class)
            ->statusField('status')
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['assignedTo'])->orderBy('created_at', 'desc'))
            ->searchableFields(['title', 'description'])
            ->enableLoadingIndicator()
            ->columns([
                KanbanColumn::make('pending')
                    ->lockCardUsing(fn(Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedClock)
                    ->allowedTransitions(['in_progress', 'archived']),

                KanbanColumn::make('in_progress')
                    ->lockCardUsing(fn(Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->allowedTransitions(['review', 'pending', 'archived']),

                KanbanColumn::make('review')
                    ->lockCardUsing(fn(Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedEye)
                    ->allowedTransitions(['completed', 'in_progress', 'archived']),

                KanbanColumn::make('completed')
                    ->lockCardUsing(fn(Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->allowedTransitions(['archived', 'pending']),

                KanbanColumn::make('archived')
                    ->lockCardUsing(fn(Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->allowedTransitions(['pending'])
            ])
            ->recordActions([
               ActionGroup::make([
                   EditAction::make('edit')
                       ->model(Task::class)
                       ->schema(fn() => $this->taskForm(null)),
                   DeleteAction::make('delete')
                       ->icon(Heroicon::OutlinedTrash)
                       ->requiresConfirmation()
                       ->color('danger'),
               ])
            ])
            ->columnHeaderActions([
                CreateAction::make()
                    ->model(Task::class)
                    ->schema(function(array $arguments): array {
                        return $this->taskForm($arguments['status']);
                    })
                ->icon(Heroicon::OutlinedPlus)
                ->hiddenLabel()
                ->link()
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
                    ->multiple()
                    ->nullable(),
            ])
            ->applyFiltersUsing(function(Builder $query, array $filters): Builder {
                if (! empty($filters['project_id'])) {
                    $query->where('project_id', $filters['project_id']);
                }
                if (! empty($filters['status'])) {
                    $query->whereIn('status', $filters['status']);
                }
                return $query;
            });
    }

    /**
     * @param $status
     * @return array
     */
    function taskForm($status): array
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
            ->icon(Heroicon::ListBullet)
        ];
    }

    public function viewAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('view')
            ->label('Task Details')
            ->slideOver()
            ->record(fn(array $arguments) => Task::query()->with(['project', 'assignedTo'])->find($arguments['recordId']))
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
