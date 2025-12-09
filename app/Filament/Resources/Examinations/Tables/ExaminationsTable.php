<?php

namespace App\Filament\Resources\Examinations\Tables;

use Filament\Tables\Table;
use App\Models\Examination;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Illuminate\Support\HtmlString;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ColumnGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Tables\Columns\CapacitySummary;

class ExaminationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn (Examination $record): string =>
                        $record->school_year . ' - ' . ucfirst($record->exam_type)
                    ),

                ColumnGroup::make('Schedule', [
                    TextColumn::make('start_date')
                        ->date()
                        ->sortable(),
                    TextColumn::make('end_date')
                        ->date()
                        ->sortable(),
                ])->alignment(Alignment::Center),

                ColumnGroup::make('Status', [
                    IconColumn::make('is_public')
                        ->label('Visible')
                        ->boolean()
                        ->tooltip(fn ($state) => $state ? 'Exam is visible to students' : 'Exam is hidden from students'),
                    IconColumn::make('application_open')
                        ->label('Accepting Applications')
                        ->boolean()
                        ->tooltip(fn ($state) => $state ? 'Students can submit applications' : 'Applications are closed'),
                ])->alignment(Alignment::Center),

                ColumnGroup::make('Overview', [
                    TextColumn::make('examination_slots_count')
                        ->label('Slots')
                        ->badge()
                        ->color('info')
                        ->sortable()
                        ->tooltip('Number of examination time slots'),

                    TextColumn::make('applications_count')
                        ->label('Applicants')
                        ->badge()
                        ->color('success')
                        ->sortable()
                        ->tooltip('Total number of applications received'),
                ])->alignment(Alignment::Center),

                CapacitySummary::make('capacity_summary')
                    ->label('Capacity'),


                // ->beforeStateUpdated(function ($record, bool $state) {
                //     if ($state) {
                //         $alreadyPublished = \App\Models\Examination::where('is_published', true)
                //             ->where('id', '!=', $record->id)
                //             ->exists();

                //         if ($alreadyPublished) {
                //             Notification::make()
                //                 ->title('Cannot publish examination')
                //                 ->body('Only one examination can be published at a time. Please unpublish the other one first.')
                //                 ->danger()
                //                 ->send();

                //             throw \Illuminate\Validation\ValidationException::withMessages([
                //                 'is_published' => 'Another examination is already published. Unpublish it first.',
                //             ]);
                //         }
                //     }
                // })
                // // ->afterStateUpdated(function ($record, bool $state) {
                // //     Notification::make()
                // //         ->title('Examination updated')
                // //         ->body($state
                // //             ? 'This examination is now published.'
                // //             : 'This examination has been unpublished.')
                // //         ->success()
                // //         ->send();
                // // })



                // ->afterStateUpdated(function ($record, bool $state) {
                //     if ($state) {
                //         Notification::make()
                //             ->title('Application Period Opened')
                //             ->body('Applicants can now submit their applications for this examination.')
                //             ->success()
                //             ->send();
                //     } else {
                //         Notification::make()
                //             ->title('Application Period Closed')
                //             ->body('Applications for this examination have been closed.')
                //             ->warning()
                //             ->send();
                //     }
                // })

                TextColumn::make('school_year')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('exam_type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([
                //
            ])
            ->recordActions([

                ActionGroup::make([
                     Action::make('manage_slots')
                    ->color('primary')
                    ->label('Manage Slots')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(function (Examination $record): string {
                        return route('filament.admin.resources.examinations.manage-slot', ['record' => $record]);
                    }),

                      Action::make('publication')

                    ->label('Publication Settings')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('gray')
                    ->disabled(fn (Examination $record): bool => $record->examinationSlots()->doesntExist())
                    ->tooltip(fn (Examination $record): ?string =>
                        $record->examinationSlots()->doesntExist()
                            ? 'Add examination slots first'
                            : null
                    )
                    ->schema([
                        Toggle::make('is_public')
                            ->label('Published')
                            ->helperText('Make exam visible to students')
                            ->required()
                            ->inline(false),

                        Toggle::make('application_open')
                            ->label('Application Open')
                            ->helperText('Allow students to submit applications')
                            ->required()
                            ->inline(false),
                    ])
                    ->fillForm(fn (Examination $record): array => [
                        'is_public' => $record->is_public,
                        'application_open' => $record->application_open,
                    ])
                    ->action(function (Examination $record, array $data): void {
                        $record->update($data);

                        Notification::make()
                            ->title('Settings updated successfully')
                            ->success()
                            ->send();
                    }),
                    Action::make('View')
                        ->label('View Details')
                        ->icon('heroicon-o-information-circle')

                        ->url(fn (Examination $record) => \App\Filament\Resources\Examinations\ExaminationResource::getUrl('examination-details', ['record' => $record])
                ),
                    EditAction::make()->color('gray'),
                    DeleteAction::make()->color('gray'),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->modifyQueryUsing(function (Builder $query) {
                $query->with(['examinationSlots.rooms'])
                    ->withCount(['examinationSlots', 'applications'])
                    ->latest();
            });
    }
}
