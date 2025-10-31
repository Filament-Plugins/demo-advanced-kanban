<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    TextInput::make('title')
                        ->required(),
                    Textarea::make('description')
                        ->columnSpanFull(),
                 Group::make([
                     Select::make('status')
                         ->options(TaskStatus::class)
                         ->required()
                         ->default(TaskStatus::PENDING),
                     Select::make('priority')
                         ->options(Priority::class)
                         ->required()
                         ->default(Priority::MEDIUM),
                 ])->columns(),
                    Group::make([
                        Select::make('project_id')
                            ->searchable()
                            ->required()
                            ->relationship('project', 'name'),
                        Select::make('assigned_to')
                            ->searchable()
                            ->relationship('assignedTo','name'),
                    ])->columns(),
                    DatePicker::make('due_date'),
                ])
                ->columnSpanFull()
            ]);
    }
}
