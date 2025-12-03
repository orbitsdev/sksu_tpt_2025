<?php

namespace App\Filament\Resources\Examinations\Pages;

use App\Filament\Resources\Examinations\ExaminationResource;
use App\Models\Campus;
use App\Models\ExaminationRoom;
use App\Models\ExaminationSlot;
use App\Models\TestCenter;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
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
                    ->with(['examination', 'testCenter.campus', 'rooms'])
            )
            ->columns([
                TextColumn::make('testCenter.campus.name')
                    ->label('Campus')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testCenter.name')
                    ->label('Test Center')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('building_name')
                    ->label('Building')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date_of_exam')
                    ->label('Exam Date')
                    ->date('M d, Y')
                    ->sortable(),

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

                Action::make('Create Slot')
                    ->icon('heroicon-o-plus-circle')
                    ->modalWidth('7xl')
                    ->modalHeading('Create Examination Slot')
                    ->modalDescription('Configure examination slot details, schedule, and room allocation')
                    ->schema([
                        Tabs::make('Tabs')
                            ->tabs([
                                // Tab 1: Location & Venue
                                Tabs\Tab::make('Location & Venue')
                                    ->icon('heroicon-o-map-pin')
                                    ->schema([
                                        Select::make('test_center_id')
                                            ->label('Test Center')
                                            ->options(function () {
                                                $testCenters = TestCenter::query()
                                                    ->where('examination_id', $this->record->id)
                                                    ->where('is_active', true)
                                                    ->with('campus')
                                                    ->get();

                                                // Debug: Log the test centers found
                                                \Log::info('Test Centers found for examination ' . $this->record->id . ': ' . $testCenters->count());

                                                if ($testCenters->isEmpty()) {
                                                    return ['_debug' => 'No test centers found for this examination'];
                                                }

                                                return $testCenters->mapWithKeys(function ($testCenter) {
                                                    return [$testCenter->id => "{$testCenter->name} ({$testCenter->campus->name})"];
                                                })->toArray();
                                            })
                                            ->searchable()
                                            ->required()
                                            ->native(false)
                                            ->helperText('Only active test centers are shown')
                                            ->live()
                                            ->columnSpanFull(),

                                        TextInput::make('building_name')
                                            ->label('Building Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g., Science Building, Main Campus Building A')
                                            ->helperText('Specify the building where the examination will be held'),
                                    ]),

                                // Tab 2: Schedule
                                Tabs\Tab::make('Schedule')
                                    ->icon('heroicon-o-calendar')
                                    ->schema([
                                        DatePicker::make('date_of_exam')
                                            ->label('Examination Date')
                                            ->required()
                                            ->minDate($this->record->start_date)
                                            ->maxDate($this->record->end_date)
                                            ->native(false)
                                            ->displayFormat('F d, Y')
                                            ->helperText(function () {
                                                $startDate = \Carbon\Carbon::parse($this->record->start_date)->format('M d, Y');
                                                $endDate = \Carbon\Carbon::parse($this->record->end_date)->format('M d, Y');
                                                return "Must be between {$startDate} and {$endDate}";
                                            }),
                                    ]),

                                // Tab 3: Capacity & Rooms
                                Tabs\Tab::make('Capacity & Rooms')
                                    ->icon('heroicon-o-building-office-2')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('slots')
                                                    ->numeric()
                                                    ->label('Total Capacity')
                                                    ->required()
                                                    ->minValue(1)
                                                    ->maxValue(10000)
                                                    ->default(50)
                                                    ->live(debounce: 500)
                                                    ->helperText('Total number of examinees that can be accommodated')
                                                    ->suffix('slots')
                                                    ->rules([
                                                        function (Get $get) {
                                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                                $rooms = (int) $get('number_of_rooms');
                                                                if ($rooms > 0 && $value % $rooms !== 0) {
                                                                    $fail("Total capacity must be evenly divisible by the number of rooms. ({$value} slots ÷ {$rooms} rooms = uneven distribution)");
                                                                }
                                                            };
                                                        },
                                                    ]),

                                                TextInput::make('number_of_rooms')
                                                    ->numeric()
                                                    ->label('Number of Rooms')
                                                    ->required()
                                                    ->minValue(1)
                                                    ->maxValue(100)
                                                    ->default(1)
                                                    ->live(debounce: 500)
                                                    ->helperText('Number of physical rooms to be used')
                                                    ->suffix('rooms')
                                                    ->rules([
                                                        function (Get $get) {
                                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                                $slots = (int) $get('slots');
                                                                if ($slots > 0 && $slots % $value !== 0) {
                                                                    $fail("Total capacity must be evenly divisible by the number of rooms. ({$slots} slots ÷ {$value} rooms = uneven distribution)");
                                                                }
                                                            };
                                                        },
                                                    ]),
                                            ]),

                                        // Live Preview
                                        Placeholder::make('room_distribution_preview')
                                            ->label('Room Distribution Preview')
                                            ->content(function (Get $get): \Illuminate\Support\HtmlString {
                                                $slots = (int) $get('slots');
                                                $rooms = (int) $get('number_of_rooms');

                                                if ($slots <= 0 || $rooms <= 0) {
                                                    return new \Illuminate\Support\HtmlString('<span style="color: gray;">Enter capacity and room count to see distribution</span>');
                                                }

                                                $capacityPerRoom = floor($slots / $rooms);
                                                $remainder = $slots % $rooms;

                                                if ($remainder === 0) {
                                                    return new \Illuminate\Support\HtmlString("<span style='color: green;'>✓ Perfect distribution: Each room will have exactly <strong>{$capacityPerRoom}</strong> slots.</span>");
                                                } else {
                                                    return new \Illuminate\Support\HtmlString("<span style='color: red;'>⚠ Uneven distribution: This configuration is not allowed. Please adjust the capacity or number of rooms so they divide evenly.</span>");
                                                }
                                            })
                                            ->helperText('Rooms must have equal capacity for fair distribution'),
                                    ]),

                                // Tab 4: Status
                                Tabs\Tab::make('Status')
                                    ->icon('heroicon-o-check-circle')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true)
                                            ->inline(false)
                                            ->helperText('When active, students can apply for this examination slot'),
                                    ]),
                            ]),
                    ])->action(function (array $data) {

                    DB::beginTransaction();
                    try {
                        $examination = $this->getRecord();

                        // Validate date range
                        $examDate = \Carbon\Carbon::parse($data['date_of_exam']);
                        $startDate = \Carbon\Carbon::parse($examination->start_date);
                        $endDate = \Carbon\Carbon::parse($examination->end_date);

                        if ($examDate->lt($startDate) || $examDate->gt($endDate)) {
                            throw new \Exception('Examination date must be between ' . $startDate->format('M d, Y') . ' and ' . $endDate->format('M d, Y'));
                        }

                        // Validate even distribution
                        if ($data['slots'] % $data['number_of_rooms'] !== 0) {
                            throw new \Exception('Total capacity must be evenly divisible by the number of rooms for fair distribution.');
                        }

                        // Create examination slot
                        $slot = ExaminationSlot::create([
                            'examination_id' => $examination->id,
                            'test_center_id' => $data['test_center_id'],
                            'building_name' => $data['building_name'],
                            'date_of_exam' => $data['date_of_exam'],
                            'slots' => $data['slots'],
                            'number_of_rooms' => $data['number_of_rooms'],
                            'is_active' => $data['is_active'],
                        ]);

                        // ✅ Auto-generate examination rooms with equal capacity
                        $rooms = [];
                        $capacityPerRoom = $data['slots'] / $data['number_of_rooms']; // Now guaranteed to be even

                        for ($i = 1; $i <= $data['number_of_rooms']; $i++) {
                            $rooms[] = [
                                'examination_slot_id' => $slot->id,
                                'room_number' => 'Room '.$i,
                                'capacity' => $capacityPerRoom,
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
