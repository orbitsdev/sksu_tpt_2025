<?php

namespace App\Filament\Resources\PersonalInformation\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class PersonalInformationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Account')
                    ->options(User::query()->whereHas('roles', fn(Builder $query) => $query->where('name', 'student'))->whereDoesntHave('personalInformation')->pluck('name', 'id'))
                    ->getSearchResultsUsing(fn(string $search): array => User::query()



                        ->where('name', 'like', "%{$search}%")
                        ->limit(50)
                        ->pluck('name', 'id')
                        ->all())
                    ->disabled(function ($operation) {
                        return $operation === 'edit';
                    })
                    ->getOptionLabelUsing(fn($value): ?string => User::find($value)?->name)
                    ->preload(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('middle_name'),
                TextInput::make('last_name')
                    ->required(),
               TextInput::make('suffix')
    ->label('Suffix')
    ->datalist(['Jr.', 'Sr.', 'II', 'III', 'IV'])
    ->placeholder('e.g. Jr.')
    ->maxLength(5),
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
