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
                    TextEntry::make('user_id')
                        ->numeric()
                        ->label('User ID'),

                    TextEntry::make('first_name')->label('First Name'),
                    TextEntry::make('middle_name')->label('Middle Name'),
                    TextEntry::make('last_name')->label('Last Name'),
                    TextEntry::make('suffix')->label('Suffix'),
                    TextEntry::make('nickname')->label('Nickname'),
                    TextEntry::make('sex')->label('Sex'),
                    TextEntry::make('birth_date')->label('Date of Birth')->date(),
                    TextEntry::make('birth_place')->label('Place of Birth'),
                    TextEntry::make('civil_status')->label('Civil Status'),
                    TextEntry::make('nationality')->label('Nationality'),
                    TextEntry::make('religion')->label('Religion'),
                ])
                ->columnSpanFull(),


            // ☎️ CONTACT INFORMATION
            Section::make('Contact Information')
                ->schema([
                    TextEntry::make('email')->label('Email Address'),
                    TextEntry::make('contact_number')->label('Contact Number'),
                ])
                ->columnSpanFull(),


            // 🏠 ADDRESS INFORMATION
            Section::make('Address Information')
                ->schema([
                    TextEntry::make('house_no')->label('House No.'),
                    TextEntry::make('street')->label('Street'),
                    TextEntry::make('barangay')->label('Barangay'),
                    TextEntry::make('municipality')->label('Municipality / City'),
                    TextEntry::make('province')->label('Province'),
                    TextEntry::make('region')->label('Region'),
                    TextEntry::make('zip_code')->label('ZIP Code'),
                ])
                ->columnSpanFull(),


            // 🕒 RECORD METADATA
            Section::make('Record Metadata')
                ->schema([
                    TextEntry::make('created_at')->label('Created At')->dateTime(),
                    TextEntry::make('updated_at')->label('Updated At')->dateTime(),
                ])
                ->columnSpanFull()

                ->collapsible(),
            ]);
    }
}
