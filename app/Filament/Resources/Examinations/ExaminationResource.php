<?php

namespace App\Filament\Resources\Examinations;

use App\Filament\Resources\Examinations\Pages\CreateExamination;
use App\Filament\Resources\Examinations\Pages\EditExamination;
use App\Filament\Resources\Examinations\Pages\ListExaminations;
use App\Filament\Resources\Examinations\Pages\ManageExaminationSlot;
use App\Filament\Resources\Examinations\Pages\ViewExamination;
use App\Filament\Resources\Examinations\Schemas\ExaminationForm;
use App\Filament\Resources\Examinations\Schemas\ExaminationInfolist;
use App\Filament\Resources\Examinations\Tables\ExaminationsTable;
use App\Models\Examination;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExaminationResource extends Resource
{

    
        public static function getNavigationSort(): ?int
{
    return 4;
}
    protected static ?string $model = Examination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Pencil;

    protected static ?string $recordTitleAttribute = 'Examination';

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

        ];
    }
}
