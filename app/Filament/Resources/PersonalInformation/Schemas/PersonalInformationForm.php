<?php

namespace App\Filament\Resources\PersonalInformation\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PersonalInformationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Student Account')
                    ->options(User::query()
                        ->whereHas('roles', fn (Builder $query) => $query->where('name', 'student'))
                        ->whereDoesntHave('personalInformation')
                        ->pluck('name', 'id'))
                    ->getSearchResultsUsing(fn (string $search): array => User::query()
                        ->where('name', 'like', "%{$search}%")
                        ->limit(50)
                        ->pluck('name', 'id')
                        ->all())
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Select the student account to link this personal information'),

                Tabs::make('Personal Information')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Name')
                                    ->schema([
                                        TextInput::make('first_name')
                                            ->label('First Name')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('middle_name')
                                            ->label('Middle Name')
                                            ->maxLength(255),

                                        TextInput::make('last_name')
                                            ->label('Last Name')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('suffix')
                                            ->label('Suffix')
                                            ->datalist(['Jr.', 'Sr.', 'II', 'III', 'IV', 'V'])
                                            ->placeholder('e.g., Jr.')
                                            ->maxLength(10),
                                    ])
                                    ->columns(4),

                                Section::make('Personal Details')
                                    ->schema([
                                        Select::make('sex')
                                            ->label('Sex')
                                            ->options([
                                                'Male' => 'Male',
                                                'Female' => 'Female',
                                            ])
                                            ->required()
                                            ->native(false),

                                        DatePicker::make('birth_date')
                                            ->label('Birth Date')
                                            ->required()
                                            ->maxDate(today())
                                            ->native(false)
                                            ->displayFormat('M d, Y'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make('Contact Information')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Contact Details')
                                    ->schema([
                                        TextInput::make('email')
                                            ->label('Email Address')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('student@example.com'),

                                        TextInput::make('contact_number')
                                            ->label('Contact Number')
                                            ->tel()
                                            ->required()
                                            ->maxLength(20)
                                            ->placeholder('09XX-XXX-XXXX'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
