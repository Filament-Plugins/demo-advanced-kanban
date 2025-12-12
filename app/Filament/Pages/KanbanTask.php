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
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\EmptyState;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class KanbanTask extends KanbanPage
{
    protected ?string $heading = 'Task Kanban';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string $columnHeaderComponent = 'kanban.task-resource.column-header';

    protected static string $cardComponent = 'kanban.task-resource.card';

    protected static ?string $navigationLabel = 'Tasks';

    protected static bool $shouldPersistFilterInSession = true;

    protected static bool $shouldPersistSearchInSession = true;

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Tasks',
            '' => 'Kanban',
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('All')
                ->icon(Heroicon::OutlinedSquare3Stack3d),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['assignedTo'])->orderBy('created_at', 'desc'))
            ->searchableFields(['title', 'description'])
            ->enableLoadingIndicator()
            ->enableFilterIndicator()
            ->columns([
                KanbanColumn::make('pending')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedClock)
                    ->allowedTransitions(['in_progress', 'archived']),

                KanbanColumn::make('in_progress')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->allowedTransitions(['review', 'pending', 'archived']),

                KanbanColumn::make('review')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedEye)
                    ->allowedTransitions(['completed', 'in_progress', 'archived']),

                KanbanColumn::make('completed')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->allowedTransitions(['archived', 'pending']),

                KanbanColumn::make('archived')
                    ->lockCardUsing(fn (Task $record) => $record->unassigned())
                    ->lockedLabel('Unassigned Tasks')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->allowedTransitions(['pending']),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make('edit')
                        ->schema(fn () => $this->taskForm(null)),
                    DeleteAction::make('delete')
                        ->icon(Heroicon::OutlinedTrash)
                        ->requiresConfirmation()
                        ->color('danger'),
                ]),
            ])
            ->columnHeaderActions([
                CreateAction::make()
                    ->schema(function (array $arguments): array {
                        return $this->taskForm($arguments['status']);
                    })
                    ->icon(Heroicon::OutlinedPlus)
                    ->hiddenLabel()
                    ->link(),
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

    public function taskForm($status): array
    {
        return [
            Select::make('project_id')
                ->required()
                ->searchable()
                ->relationship('project', 'name'),

            TextInput::make('title')
                ->required()
                ->maxLength(255),

            Group::make([
                Select::make('status')
                    ->options(TaskStatus::class)
                    ->default($status),
                Select::make('priority')
                    ->required()
                    ->options(Priority::class),
            ])->columns(),

            Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),

            Select::make('assigned_to')
                ->searchable()
                ->relationship('assignedTo', 'name')
                ->nullable(),

            Repeater::make('attachments')
                ->hiddenLabel()
                ->relationship('attachments')
                ->deletable(fn(Get $get) => count($get('attachments')) > 1)
                ->columnSpanFull()
                ->schema([
                    FileUpload::make('file_path')
                        ->visibility('public')
                        ->disk('public')
                    ->label('Attachment'),
                ])->defaultItems(1),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Task List')
                ->url(ListTasks::getUrl())
                ->icon(Heroicon::ListBullet),
        ];
    }

    public function viewAction(): Action
    {
        return Action::make('view')
            ->label('Task Details')
            ->slideOver()
            ->record(fn (array $arguments) => Task::query()->with(['project', 'assignedTo'])->find($arguments['recordId']))
            ->modalSubmitAction(false)
            ->modalWidth(Width::SevenExtraLarge)
            ->schema(function ($record) {
                return [

//                    Image::make(asset('01KC9WKYTC0JRKSV25Q9M807YS.png'), 'Task Image'),
                    Grid::make(3)
                    ->schema([
                        Group::make([
                            TextEntry::make('Title')
                                ->default($record->title),
                            TextEntry::make('Project')
                                ->default($record->project->name),

                            TextEntry::make('Description')
                                ->default($record->description),

                            TextEntry::make('Assigned To')
                                ->badge()
                                ->default($record->assignedTo?->name ?? 'Unassigned'),

                        ])->columnSpan(2),
                        Group::make([
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

                            TextEntry::make('due_date')
                                ->default($record->due_date?->toFormattedDateString() ?? 'No due date'),
                        ])
                    ]),

                    Section::make('Attachments')
                        ->contained(false)
                        ->schema([
                            RepeatableEntry::make('attachments')
                                ->grid(4)
                                ->state(fn() => $record->attachments->map(fn($attachment) => [
                                    'file_path' => asset($attachment->file_path),
                                ])->toArray())
                                ->hiddenLabel()
                                ->contained(false)
                                ->model(fn() => $record)
                                ->schema([
                                    ImageEntry::make('file_path')
                                        ->visibility('public')
                                    ->hiddenLabel(),
                                ])
                        ]),

                      EmptyState::make('no_attachments')
                          ->visible(fn() => $record->attachments->isEmpty())
                          ->heading('No Attachments')
                          ->description('There are no attachments for this task.')
                          ->icon(Heroicon::OutlinedPaperClip),

                ];
            });
    }
}
