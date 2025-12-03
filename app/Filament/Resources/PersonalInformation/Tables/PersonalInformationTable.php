<?php

namespace App\Filament\Resources\PersonalInformation\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PersonalInformationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('user', fn (Builder $query) => $query->whereHas('roles', fn (Builder $query) => $query->where('name', 'student')))->latest())
            ->striped()
            ->columns([
                TextColumn::make('user.email')->searchable(),
                TextColumn::make('first_name')
                    ->searchable()->label('Email'),
                TextColumn::make('middle_name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->searchable(),
                TextColumn::make('suffix')
                    ->searchable(),
                TextColumn::make('nickname')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sex'),
                TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('birth_place')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('civil_status')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nationality')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('religion')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact_number')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('house_no')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('street')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('barangay')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('municipality')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('province')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('region')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('zip_code')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->button()->color('primary'),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
