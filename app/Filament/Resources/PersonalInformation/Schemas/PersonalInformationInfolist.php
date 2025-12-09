<?php

namespace App\Filament\Resources\PersonalInformation\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonalInformationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                ->schema([
                    TextEntry::make('user.email')
                        ->label('User Email'),

                    TextEntry::make('first_name')->label('First Name'),
                    TextEntry::make('middle_name')->label('Middle Name'),
                    TextEntry::make('last_name')->label('Last Name'),
                    TextEntry::make('suffix')->label('Suffix'),
                    TextEntry::make('sex')->label('Sex'),
                    TextEntry::make('birth_date')->label('Date of Birth')->date(),
                ])
                ->columns(3)
                ->columnSpanFull(),

            Section::make('Contact Information')
                ->schema([
                    TextEntry::make('email')->label('Email Address'),
                    TextEntry::make('contact_number')->label('Contact Number'),
                ])
                ->columns(2)
                ->columnSpanFull(),

            Section::make('Record Metadata')
                ->schema([
                    TextEntry::make('created_at')->label('Created At')->dateTime(),
                    TextEntry::make('updated_at')->label('Updated At')->dateTime(),
                ])
                ->columns(2)
                ->columnSpanFull()
                ->collapsible(),
            ]);
    }
}
