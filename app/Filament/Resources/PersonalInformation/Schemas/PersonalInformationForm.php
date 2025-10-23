<?php

namespace App\Filament\Resources\PersonalInformation\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PersonalInformationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('middle_name'),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('suffix'),
                TextInput::make('nickname'),
                Select::make('sex')
                    ->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other']),
                DatePicker::make('birth_date'),
                TextInput::make('birth_place'),
                TextInput::make('civil_status'),
                TextInput::make('nationality'),
                TextInput::make('religion'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('contact_number'),
                TextInput::make('house_no'),
                TextInput::make('street'),
                TextInput::make('barangay'),
                TextInput::make('municipality'),
                TextInput::make('province'),
                TextInput::make('region'),
                TextInput::make('zip_code'),
            ]);
    }
}
