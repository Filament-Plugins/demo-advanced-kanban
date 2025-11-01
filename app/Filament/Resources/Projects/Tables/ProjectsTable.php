<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Filament\Resources\Projects\Pages\ManageProjectTasks;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('own_by')
                ->relationship('owner', 'name'),
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->recordUrl(fn (Project $record) => ProjectResource::getUrl('tasks-kanban', ['record' => $record]))
            ->recordActions([
                Action::make('kanban')
                    ->url(fn (Project $record) => ProjectResource::getUrl('tasks-kanban', ['record' => $record]))
                    ->icon(Heroicon::OutlinedSquares2x2),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
