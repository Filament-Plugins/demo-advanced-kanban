<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    TextEntry::make('title'),
                    TextEntry::make('description')
                        ->placeholder('-')
                        ->columnSpanFull(),
                    Group::make([TextEntry::make('status')->badge(),
                        TextEntry::make('priority')->badge(),
                        TextEntry::make('due_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('project.name')
                            ->label('Project')
                            ->numeric(),
                        TextEntry::make('assignedTo.name')
                            ->label('Assigned To')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        ])->columns(3),
                     TextEntry::make('tags.name')
                         ->label('Tags')
                         ->badge(),
                ])->columnSpanFull()
            ]);
    }
}
