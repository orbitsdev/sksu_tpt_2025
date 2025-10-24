<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgramInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('campus_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('abbreviation'),
                TextEntry::make('code'),
                IconEntry::make('is_offered')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
