<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('User Information')
                    ->tabs([
                        Tab::make('Account')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Section::make('Account Information')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Full Name'),

                                        TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),

                                        TextInput::make('password')
                                            ->password()
                                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                                            ->required(fn (string $operation): bool => $operation === 'create')
                                            ->dehydrated(fn ($state) => filled($state))
                                            ->maxLength(255)
                                            ->helperText('Leave blank to keep current password'),

                                        Select::make('campus_id')
                                            ->label('Campus')
                                            ->relationship('campus', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),

                                        Select::make('roles')
                                            ->relationship('roles', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->required()
                                            ->label('Role(s)'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make('Personal Information')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Name')
                                    ->relationship('personalInformation')
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
                                    ->relationship('personalInformation')
                                    ->schema([

                                        ToggleButtons::make('sex')
                                        ->inline()
                                            ->options([
                                                'Male' => 'Male',
                                                'Female' => 'Female',
                                            ]),

                                        DatePicker::make('birth_date')
                                            ->label('Birth Date')
                                            ->required()
                                            ->maxDate(today())
                                            ->native(false)
                                            ->displayFormat('M d, Y'),
                                    ])
                                    ->columns(2),

                                Section::make('Contact Details')
                                    ->relationship('personalInformation')
                                    ->schema([
                                        TextInput::make('email')
                                            ->label('Email Address')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('user@example.com'),

                                        TextInput::make('contact_number')
                                            ->label('Contact Number')
                                            ->mask('99999999999')
                                            ->length(11)
                                            ->required()

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
