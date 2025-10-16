<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('priority')
                    ->required()
                    ->default('medium'),
                DatePicker::make('due_date'),
                TextInput::make('project_id')
                    ->required()
                    ->numeric(),
                TextInput::make('assigned_to')
                    ->numeric(),
            ]);
    }
}
