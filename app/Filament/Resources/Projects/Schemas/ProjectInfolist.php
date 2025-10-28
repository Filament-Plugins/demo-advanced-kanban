<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make()
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('owner.name')
                        ->label('Owner'),
                    TextEntry::make('description')
                        ->placeholder('-')
                        ->columnSpanFull(),

                    TextEntry::make('created_at')
                        ->badge()
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->badge()
                        ->placeholder('-'),
                ])
                ->columnSpanFull()
            ]);
    }
}
