<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    Group::make([
                        TextInput::make('name')
                            ->required(),
                        Select::make('owner_id')
                            ->relationship('owner', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])->columns(),
                    Textarea::make('description')
                        ->columnSpanFull(),

                ])
                ->columnSpanFull()
            ]);
    }
}
