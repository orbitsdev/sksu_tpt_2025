<?php

namespace App\Filament\Resources\Examinations\Tables;

use App\Filament\Tables\Columns\CapacitySummary;
use App\Models\Examination;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ExaminationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                //     TextColumn::make('total_slots')
                // ->sortable(),
                // TextColumn::make('examination_slots')
                //     ->label('Campuses & Slots')
                //     ->html()
                //     ->getStateUsing(function ($record) {
                //         $slots = $record->examinationSlots()
                //             ->with('campus')
                //             ->get(['campus_id', 'slots', 'is_active']);

                //         if ($slots->isEmpty()) {
                //             return '';
                //         }

                //         // Create a simple line-separated list (no bullets)
                //         $list = $slots->map(function ($slot) {
                //             $campusName = e(optional($slot->campus)->name ?? 'Unknown Campus');
                //             $slotsCount = e($slot->slots);

                //             return "{$campusName} — <strong>{$slotsCount}</strong>";
                //         })->implode('<br>'); // 👈 line break instead of bullets

                //         return new HtmlString($list);
                //     })
                //     ->toggleable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('venue')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
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
                ,
                IconColumn::make('is_application_open')
                    ->boolean()
                    ->label('Application Open'),
                CapacitySummary::make('capacity_summary')->label('Capacity Overview'),
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
                TextColumn::make('type')
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
                Action::make('manage_slots')
                    ->button()
                    ->label('Manage Slots')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(function (Examination $record): string {
                        return route('filament.admin.resources.examinations.manage-slot', ['record' => $record]);
                    }),

                Action::make('settings')
                    ->button()
                    ->label('Settings')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('gray')
                    ->disabled(fn (Examination $record): bool => $record->examinationSlots()->doesntExist())
                    ->tooltip(fn (Examination $record): ?string =>
                        $record->examinationSlots()->doesntExist()
                            ? 'Add examination slots first'
                            : null
                    )
                    ->form([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Make exam visible to students')
                            ->required()
                            ->inline(false),

                        Toggle::make('is_application_open')
                            ->label('Application Open')
                            ->helperText('Allow students to submit applications')
                            ->required()
                            ->inline(false),
                    ])
                    ->fillForm(fn (Examination $record): array => [
                        'is_published' => $record->is_published,
                        'is_application_open' => $record->is_application_open,
                    ])
                    ->action(function (Examination $record, array $data): void {
                        $record->update($data);

                        Notification::make()
                            ->title('Settings updated successfully')
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
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
                    // ->withSum('examination_slots.rooms as total_capacity', 'capacity')
                    // ->withSum('examination_slots.rooms as total_occupied', 'occupied')
                    ->latest();
            });
    }
}
