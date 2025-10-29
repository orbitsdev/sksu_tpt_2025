<?php
namespace App\Filament\Resources\Examinations\Tables;

use Filament\Tables\Table;
use App\Models\Examination;

use Filament\Actions\Action;
use App\Models\ExaminationSlot;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class ExaminationSlotTable
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
                    TextInput::make('title')->required(),



                ])->action(function (array $data) {

                    dd($data);
                })
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
