<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Pages\KanbanTask;
use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Task Kanban')
                ->url(KanbanTask::getUrl())
                ->icon(Heroicon::OutlinedSquares2x2),
              CreateAction::make(),
        ];
    }
}
