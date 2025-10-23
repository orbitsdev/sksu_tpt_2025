<?php

namespace App\Filament\Resources\PersonalInformation;

use App\Filament\Resources\PersonalInformation\Pages\CreatePersonalInformation;
use App\Filament\Resources\PersonalInformation\Pages\EditPersonalInformation;
use App\Filament\Resources\PersonalInformation\Pages\ListPersonalInformation;
use App\Filament\Resources\PersonalInformation\Pages\ViewPersonalInformation;
use App\Filament\Resources\PersonalInformation\Schemas\PersonalInformationForm;
use App\Filament\Resources\PersonalInformation\Schemas\PersonalInformationInfolist;
use App\Filament\Resources\PersonalInformation\Tables\PersonalInformationTable;
use App\Models\PersonalInformation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PersonalInformationResource extends Resource
{
    protected static ?string $model = PersonalInformation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'PersonalInformation';

    public static function form(Schema $schema): Schema
    {
        return PersonalInformationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PersonalInformationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonalInformationTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPersonalInformation::route('/'),
            'create' => CreatePersonalInformation::route('/create'),
            'view' => ViewPersonalInformation::route('/{record}'),
            'edit' => EditPersonalInformation::route('/{record}/edit'),
        ];
    }
}
