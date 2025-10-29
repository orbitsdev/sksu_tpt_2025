<?php

namespace App\Filament\Resources\Examinations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExaminationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
    //                 TextInput::make('total_slots')
    // ->label('Total Slots')
    // ->numeric()
    // ->minValue(0)
    // ->required()
    // ->default(0)
    // ->helperText('Total number of available slots for examinees.'),

                TextInput::make('venue'),
                Toggle::make('is_published')
                    ->required(),
                Toggle::make('is_application_open')
                    ->required(),
                TextInput::make('school_year'),
                TextInput::make('type'),
            ]);
    }
}
