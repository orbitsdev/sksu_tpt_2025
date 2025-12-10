<?php

namespace App\Filament\Resources\Examinations;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use App\Models\Examination;
use Filament\Schemas\Schema;

use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Examinations\Pages\EditExamination;
use App\Filament\Resources\Examinations\Pages\ViewExamination;
use App\Filament\Resources\Examinations\Pages\ListExaminations;
use App\Filament\Resources\Examinations\Pages\CreateExamination;
use App\Filament\Resources\Examinations\Schemas\ExaminationForm;
use App\Filament\Resources\Examinations\Pages\ExaminationDetails;
use App\Filament\Resources\Examinations\Tables\ExaminationsTable;
use App\Filament\Resources\Examinations\Schemas\ExaminationInfolist;

class ExaminationResource extends Resource
{
protected static string | UnitEnum | null $navigationGroup = 'Management';

        public static function getNavigationSort(): ?int
{
    return 4;
}
    protected static ?string $model = Examination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?string $recordTitleAttribute = 'title';

    protected static bool $canCreateAnother = false;

    public static function form(Schema $schema): Schema
    {
        return ExaminationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExaminationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExaminationsTable::configure($table);
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
            'index' => ListExaminations::route('/'),
            'create' => CreateExamination::route('/create'),
            'view' => ViewExamination::route('/{record}'),
            'edit' => EditExamination::route('/{record}/edit'),
            'manage-slot' => Pages\ManageSlot::route('/{record}/manage-slot'),
            'examination-details' => ExaminationDetails::route('/{record}/examination-details'),

        ];
    }
}
