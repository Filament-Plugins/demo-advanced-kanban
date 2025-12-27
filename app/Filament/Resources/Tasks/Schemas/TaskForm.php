<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Task Details')
                ->schema([
                    Select::make('project_id')
                        ->required()
                        ->searchable()
                        ->relationship('project', 'name'),

                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),

                    Group::make([
                        Select::make('status')
                            ->required()
                            ->options(TaskStatus::class),

                        Select::make('priority')
                            ->required()
                            ->options(Priority::class),

                        DatePicker::make('due_date')
                            ->minDate(today())
                            ->nullable(),
                    ])->columns(3),

                    Textarea::make('description')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    Select::make('assigned_to')
                        ->searchable()
                        ->relationship('assignedTo', 'name')
                        ->nullable(),

                    Select::make('tags')
                        ->label('Tags')
                        ->multiple()
                        ->preload()
                        ->relationship('tags', 'name')
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
                    ])->columnSpanFull()
                ]);
    }
}
