<?php

namespace App\Filament\Resources\Examinations\Schemas;

use App\Models\TestCenter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ExaminationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->placeholder('e.g., First Semester Entrance Exam 2025'),

                Select::make('exam_type')
                    ->label('Type')
                    ->options([
                        'Entrance' => 'Entrance',
                        'Midterm' => 'Midterm',
                        'Final' => 'Final',
                    ])
                    ->default('Entrance')
                    ->required()
                    ->native(false),

                TextInput::make('school_year')
                    ->label('School Year')
                    ->mask('9999-9999')
                    ->placeholder('YYYY-YYYY')
                    ->default(date('Y') . '-' . (date('Y') + 1))
                    ->required()
                    ->maxLength(9),

                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->minDate(today())
                    ->native(false)
                    ->displayFormat('M d, Y')
                    ->placeholder('Select start date')
                    ->live(),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->minDate(fn (Get $get) => $get('start_date') ?: today())
                    ->afterOrEqual('start_date')
                    ->native(false)
                    ->displayFormat('M d, Y')
                    ->placeholder('Select end date'),
            ])
            ->columns(2);
    }
}
