<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManageProjectTasks extends ManageRelatedRecords
{
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'tasks';

    protected static bool $shouldRegisterNavigation = false;

    public function table(Table $table): Table
    {
        return TasksTable::configure($table)
            ->modifyQueryUsing(fn ($query) => $query->where('project_id', $this->getOwnerRecord()?->id));
    }

    public function form(Schema $schema): Schema
    {
        return TaskForm::configure($schema);
    }

    protected function getHeaderActions(): array
    {
        return [

            \Filament\Actions\Action::make('Kanban View')
                ->url(fn () => ManageProjectTasksKanban::getUrl(['record' => $this->getOwnerRecord()]))
                ->icon(Heroicon::OutlinedSquares2x2),
            CreateAction::make('create')
                ->model(Task::class),
        ];
    }
}
