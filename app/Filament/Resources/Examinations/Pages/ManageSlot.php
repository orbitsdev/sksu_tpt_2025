<?php

namespace App\Filament\Resources\Examinations\Pages;

use App\Models\Campus;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\ExaminationSlot;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Filament\Resources\Examinations\ExaminationResource;

class ManageSlot extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithRecord;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string $resource = ExaminationResource::class;

    protected string $view = 'filament.resources.examinations.pages.manage-slot';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

    }

    public function addSlotAction(): Action
    {
        return Action::make('addSlot')
            ->schema([
                TextInput::make('title'),
            ])
            ->action(function (array $data) {
                dd($data);
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ExaminationSlot::query())
            ->columns([
                TextColumn::make('examination.title')->searchable(),
                TextColumn::make('campus.name')->searchable(),
                TextColumn::make('building_name')->searchable(),
                TextColumn::make('slots')->money(),
                TextColumn::make('date_of_exam')->date(),
                ToggleColumn::make('is_active'),
            ])
            ->headerActions([

                Action::make('Create Slot')->schema([
                    // TextInput::make('title')->required(),
                    Select::make('campus_id')
                        ->label('Campus')
                        ->options(Campus::query()->whereDoesntHave('examinationSlots', function ($query) {
                            $query->where('examination_id', $this->record->id);
                        })->pluck('name', 'id'))
                        ->getSearchResultsUsing(fn (string $search): array => Campus::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('name', 'id')
                            ->all())
                        ->getOptionLabelUsing(fn ($value): ?string => Campus::find($value)?->name)
                        ->preload(),
                    TextInput::make('building_name')->required(),
                    TextInput::make('slots')->required()->mask('999999999'),
                  DatePicker::make('date_of_exam')
    ->label('Date of Examination')
    ->required()
    ->minDate(now()),
    Toggle::make('is_active')->required()->label('Active'),

                ])->action(function (array $data) {

                    DB::beginTransaction();

        try {
            $examination = $this->getRecord();

            ExaminationSlot::create([
                'examination_id' => $examination->id,
                'campus_id' => $data['campus_id'],
                'building_name' => $data['building_name'],
                'slots' => $data['slots'],
                'date_of_exam' => $data['date_of_exam'],
                'is_active' => $data['is_active'],
            ]);

            DB::commit();

            Notification::make()
                ->title('Slot Created Successfully')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error Creating Slot')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }



                }),
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
