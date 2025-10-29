<?php

namespace App\Filament\Resources\Examinations\Pages;

use App\Filament\Resources\Examinations\ExaminationResource;
use App\Models\Campus;
use App\Models\ExaminationRoom;
use App\Models\ExaminationSlot;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

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

    public function getTitle(): string|Htmlable
    {
        // Supposons que $this->record est l'enregistrement du modèle que vous visualisez.

        return "{$this->record?->title} Slots";
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
            ->query(
                ExaminationSlot::query()
                    ->where('examination_id', $this->record->id)
                    ->with(['examination', 'campus', 'rooms'])
            )
            ->columns([
                TextColumn::make('campus.name')
                    ->label('Campus')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('building_name')
                    ->label('Building')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date_of_exam')
                    ->label('Exam Date')
                    ->date(),

                     TextColumn::make('total_capacity')
                    ->label('Total Capacity')
                    ->alignCenter()

                    ->getStateUsing(fn ($record) => $record->rooms->sum('capacity')),

                TextColumn::make('number_of_rooms')
                    ->label('# Rooms')
                    ->alignCenter(),

                // 🧮 Total capacity (sum of room capacities)


                // 👥 Occupied seats (sum of room.occupied)
                TextColumn::make('occupied')
                    ->label('Occupied')
                    ->alignCenter()

                    ->getStateUsing(fn ($record) => $record->rooms->sum('occupied')),

                // 🎯 Remaining = capacity - occupied
                TextColumn::make('remaining')
                    ->label('Available')
                    ->alignCenter()

                    ->getStateUsing(function ($record) {
                        $capacity = $record->rooms->sum('capacity');
                        $occupied = $record->rooms->sum('occupied');
                        $remaining = max($capacity - $occupied, 0);
                        $color = $remaining > 0 ? 'green' : 'red';

                        return new \Illuminate\Support\HtmlString("<strong style='color:{$color}'>{$remaining}</strong>");
                    }),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->alignCenter(),
            ])
            ->headerActions([

                Action::make('Create Slot')->schema([
                    Select::make('campus_id')
                        ->label('Campus')
                        ->options(Campus::query()
                            ->whereDoesntHave('examinationSlots', function ($query) {
                                $query->where('examination_id', $this->record->id);
                            })
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('building_name')
                        ->label('Building Name')
                        ->required(),

                    DatePicker::make('date_of_exam')
                        ->label('Date of Examination')
                        ->required()
                        ->minDate(now()),

                    TextInput::make('slots')
                        ->numeric()
                        ->label('Total Slots')
                        ->required()
                        ->minValue(1)
                        ->helperText('Total examinees that can be accommodated.'),
                    TextInput::make('number_of_rooms')
                        ->numeric()
                        ->label('Number of Rooms')
                        ->required()
                        ->minValue(1)
                        ->helperText('Number of physical rooms available.'),


                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                ])->action(function (array $data) {

                    DB::beginTransaction();
                    try {
                        $examination = $this->getRecord();

                        // Create examination slot
                        $slot = ExaminationSlot::create([
                            'examination_id' => $examination->id,
                            'campus_id' => $data['campus_id'],
                            'building_name' => $data['building_name'],
                            'date_of_exam' => $data['date_of_exam'],
                            'slots' => $data['slots'],
                            'number_of_rooms' => $data['number_of_rooms'],
                            'is_active' => $data['is_active'],
                        ]);

                        // ✅ Auto-generate examination rooms
                        $rooms = [];
                        $capacityPerRoom = floor($data['slots'] / $data['number_of_rooms']);
                        $remainder = $data['slots'] % $data['number_of_rooms'];

                        for ($i = 1; $i <= $data['number_of_rooms']; $i++) {
                            $capacity = $capacityPerRoom + ($i === 1 ? $remainder : 0); // handle uneven division
                            $rooms[] = [
                                'examination_slot_id' => $slot->id,
                                'room_number' => 'Room '.$i,
                                'capacity' => $capacity,
                                'occupied' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        ExaminationRoom::insert($rooms);

                        DB::commit();

                        Notification::make()
                            ->title('Slot Created')
                            ->body('Slot and corresponding rooms generated successfully.')
                            ->success()
                            ->send();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        Notification::make()
                            ->title('Error')
                            ->body($th->getMessage())
                            ->danger()
                            ->send();
                    }

                }),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([

                Action::make('advance')
                    ->label('View Rooms')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn ($action) => $action->label('Close'))
                    ->disabledForm()
                    ->modalContent(fn ($record): View => view(
                        'filament.resources.examinations.pages.examination-slot-rooms',
                        ['record' => $record],
                    ))
                    ->modalWidth(Width::SevenExtraLarge),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                // ...
            ]);
    }
}
