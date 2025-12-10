<?php

namespace App\Filament\Resources\TestCenters;

use App\Filament\Resources\TestCenters\Pages\CreateTestCenter;
use App\Filament\Resources\TestCenters\Pages\EditTestCenter;
use App\Filament\Resources\TestCenters\Pages\ListTestCenters;
use App\Filament\Resources\TestCenters\Pages\ViewTestCenter;
use App\Filament\Resources\TestCenters\Schemas\TestCenterForm;
use App\Filament\Resources\TestCenters\Schemas\TestCenterInfolist;
use App\Filament\Resources\TestCenters\Tables\TestCentersTable;
use App\Models\TestCenter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TestCenterResource extends Resource
{
    protected static string|UnitEnum|null $navigationGroup = 'Management';

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    protected static ?string $model = TestCenter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::MapPin;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TestCenterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TestCenterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestCentersTable::configure($table);
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
            'index' => ListTestCenters::route('/'),
            'create' => CreateTestCenter::route('/create'),
            'view' => ViewTestCenter::route('/{record}'),
            'edit' => EditTestCenter::route('/{record}/edit'),
        ];
    }
}
