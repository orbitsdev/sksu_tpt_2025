<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\ExaminationSlot;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class ExaminationSlotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
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
                Action::make('viewUser')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                              Section::make('Details')
                                  ->schema([
                                      TextInput::make('name'),
                                      Select::make('position')
                                          ->options([
                                              'developer' => 'Developer',
                                              'designer' => 'Designer',
                                          ]),
                                      Checkbox::make('is_admin'),
                                  ]),
                              Section::make('Auditing')
                                  ->schema([
                                      TextEntry::make('created_at')
                                          ->dateTime(),
                                      TextEntry::make('updated_at')
                                          ->dateTime(),
                                  ]),
                            ]),
                    ]),
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
