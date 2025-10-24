<?php

namespace App\Filament\Resources\Examinations\Tables;

use Mockery\Matcher\Not;
use Filament\Tables\Table;
use App\Models\Examination;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Validation\ValidationException;
class ExaminationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('venue')
                    ->searchable()  ->toggleable(isToggledHiddenByDefault: true),



                    ToggleColumn::make('is_published')
    ->label('Published')
    ->beforeStateUpdated(function ($record, bool $state) {
        if ($state) {
            $alreadyPublished = \App\Models\Examination::where('is_published', true)
                ->where('id', '!=', $record->id)
                ->exists();

            if ($alreadyPublished) {
                Notification::make()
                    ->title('Cannot publish examination')
                    ->body('Only one examination can be published at a time. Please unpublish the other one first.')
                    ->danger()
                    ->send();

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'is_published' => 'Another examination is already published. Unpublish it first.',
                ]);
            }
        }
    })
    ->afterStateUpdated(function ($record, bool $state) {
        Notification::make()
            ->title('Examination updated')
            ->body($state
                ? 'This examination is now published.'
                : 'This examination has been unpublished.')
            ->success()
            ->send();
    }),
              ToggleColumn::make('is_application_open')
    ->label('Application Open')

    ->afterStateUpdated(function ($record, bool $state) {
        if ($state) {
            Notification::make()
                ->title('Application Period Opened')
                ->body('Applicants can now submit their applications for this examination.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Application Period Closed')
                ->body('Applications for this examination have been closed.')
                ->warning()
                ->send();
        }
    }),


                TextColumn::make('school_year')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
->toggleable(isToggledHiddenByDefault: true)
                    ,
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                   ViewAction::make(),
                ActionGroup::make([
                   EditAction::make(),
                      DeleteAction::make(),
                ])


            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
