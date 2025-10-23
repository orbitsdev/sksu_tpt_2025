<?php

namespace App\Filament\Resources\PersonalInformation\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PersonalInformationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('first_name'),
                TextEntry::make('middle_name'),
                TextEntry::make('last_name'),
                TextEntry::make('suffix'),
                TextEntry::make('nickname'),
                TextEntry::make('sex'),
                TextEntry::make('birth_date')
                    ->date(),
                TextEntry::make('birth_place'),
                TextEntry::make('civil_status'),
                TextEntry::make('nationality'),
                TextEntry::make('religion'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('contact_number'),
                TextEntry::make('house_no'),
                TextEntry::make('street'),
                TextEntry::make('barangay'),
                TextEntry::make('municipality'),
                TextEntry::make('province'),
                TextEntry::make('region'),
                TextEntry::make('zip_code'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
