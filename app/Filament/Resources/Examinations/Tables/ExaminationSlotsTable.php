<?php

namespace App\Filament\Resources\Examinations\Tables;

use Mockery\Matcher\Not;
use Filament\Tables\Table;
use App\Models\Examination;
use Filament\Actions\Action;
use App\Models\ExaminationSlot;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class ExaminationSlotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(ExaminationSlot::query())
            ->columns([
            TextColumn::make('examination.title')->searchable(),
                TextColumn::make('campus.name')->searchable(),
                TextColumn::make('building_name')->searchable(),
                TextColumn::make('slots'),
                TextColumn::make('date_of_exam')->date(),
                ToggleColumn::make('is_active'),
            ])

              ->headerActions([
                Action::make('Create Slot')->schema([


                ])->model(ExaminationSlot::class)
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                // ...
            ]);
        }
}
