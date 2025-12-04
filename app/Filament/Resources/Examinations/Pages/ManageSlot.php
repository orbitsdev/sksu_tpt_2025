<?php

namespace App\Filament\Resources\Examinations\Pages;

use App\Filament\Resources\Examinations\ExaminationResource;
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
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
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
use Illuminate\Support\HtmlString;

/**
 * Examination Slot Management
 *
 * DESIGN PRINCIPLE: Slots are IMMUTABLE once students are assigned
 *
 * Why we don't allow editing slots with assigned students:
 * 1. Data Integrity - Changing capacity/rooms would invalidate existing seat assignments
 * 2. Student Confusion - Students already have assigned rooms/seats that would change
 * 3. Complexity - Cascading changes to rooms, seats, and application_slots records
 * 4. Audit Trail - Need to preserve original slot configuration
 *
 * Instead of editing: CREATE NEW SLOTS
 * - Need more capacity? → Create additional slot (same or different date/building)
 * - Wrong configuration? → Deactivate old slot, create new one
 * - Different location? → Create new slot with correct building
 *
 * Protection Rules:
 * - DELETE: Only allowed if no students assigned (application_slots.count = 0)
 * - EDIT: Not implemented - create new slot instead
 * - DEACTIVATE: Always allowed via is_active toggle (prevents new applications)
 */
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
                    ->withCount('applicationSlots')
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

                TextColumn::make('occupied')
                    ->label('Occupied')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->rooms->sum('occupied')),

                TextColumn::make('remaining')
                    ->label('Available')
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        $capacity = $record->rooms->sum('capacity');
                        $occupied = $record->rooms->sum('occupied');
                        $remaining = max($capacity - $occupied, 0);
                        $color = $remaining > 0 ? 'green' : 'red';

                        return new HtmlString("<strong style='color:{$color}'>{$remaining}</strong>");
                    }),

                TextColumn::make('assigned_students')
                    ->label('Assigned Students')
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($record): string => $record->hasAssignedStudents() ? 'warning' : 'gray')
                    ->icon(fn ($record): string => $record->hasAssignedStudents() ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open')
                    ->getStateUsing(fn ($record) => $record->assigned_students_count)
                    ->tooltip(fn ($record): string =>
                        $record->hasAssignedStudents()
                            ? 'Students assigned - slot cannot be deleted'
                            : 'No students assigned - slot can be deleted'
                    ),

                // ToggleColumn::make('is_active')
                //     ->label('Active')
                //     ->alignCenter(),
            ])
            ->headerActions([

                Action::make('Create Slot')
                    ->icon('heroicon-o-plus-circle')
                    ->modalWidth('6xl')
                    ->modalHeading('Create Examination Slot')
                    ->modalDescription('Follow the steps to configure your examination slot')
                    ->modalSubmitActionLabel('Create Slot')
                    ->schema([
                        Wizard::make([
                            // Step 1: Location & Venue
                            Wizard\Step::make('Location & Venue')
                                ->icon('heroicon-o-map-pin')
                                ->description('Select test center and building')
                                ->schema([
                                   Select::make('test_center_id')
                                                ->label('Test Center')
                                                ->options(function () {
                                                    // Get ALL active test centers
                                                    return TestCenter::query()
                                                        ->where('is_active', true)
                                                        ->with('campus')
                                                        ->get()
                                                        ->mapWithKeys(function ($testCenter) {
                                                            return [$testCenter->id => "{$testCenter->name} ({$testCenter->campus->name})"];
                                                        })
                                                        ->toArray();
                                                })
                                                ->searchable()
                                                ->required()
                                                ->native(false)
                                                ->helperText('Select any active test center for this examination slot')
                                                ->live(),

                                            TextInput::make('building_name')
                                                ->label('Building Name')
                                                ->required()
                                                ->maxLength(255)
                                                ->placeholder('e.g., Science Building, Main Campus Building A')
                                                ->helperText('Specify the building where the examination will be held'),
                                ]),

                            // Step 2: Schedule
                            Wizard\Step::make('Schedule')
                                ->icon('heroicon-o-calendar')
                                ->description('Choose examination date')
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

                            // Step 3: Capacity & Rooms
                            Wizard\Step::make('Capacity & Rooms')
                                ->icon('heroicon-o-building-office-2')
                                ->description('Configure capacity and rooms')
                                ->schema([
                                     Grid::make(2)
                                                ->schema([
                                                    TextInput::make('total_examinees')
                                                        ->numeric()
                                                        ->label('Total Examinees')
                                                        ->required()
                                                        ->minValue(1)
                                                        ->maxValue(10000)
                                                        ->default(50)
                                                        ->live(debounce: 500)
                                                        ->helperText('Total number of examinees expected')
                                                        ->suffix('examinees')
                                                        ->rules([
                                                            function (Get $get) {
                                                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                                    $rooms = (int) $get('number_of_rooms');
                                                                    if ($rooms > 0 && $value % $rooms !== 0) {
                                                                        $fail("Total examinees must be evenly divisible by the number of rooms. ({$value} examinees ÷ {$rooms} rooms = uneven distribution)");
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
                                                                    $total = (int) $get('total_examinees');
                                                                    if ($total > 0 && $total % $value !== 0) {
                                                                        $fail("Total examinees must be evenly divisible by the number of rooms. ({$total} examinees ÷ {$value} rooms = uneven distribution)");
                                                                    }
                                                                };
                                                            },
                                                        ]),
                                                ]),

                                            // Live Preview
                                            Placeholder::make('room_distribution_preview')
                                                ->label('Distribution Preview')
                                                ->content(function (Get $get): HtmlString {
                                                    $total = (int) $get('total_examinees');
                                                    $rooms = (int) $get('number_of_rooms');

                                                    if ($total <= 0 || $rooms <= 0) {
                                                        return new HtmlString('<div style="padding: 0.75rem; background-color: #f9fafb; border-radius: 0.375rem; border: 1px solid #e5e7eb;"><span style="color: #6b7280;">Enter values to see distribution preview</span></div>');
                                                    }

                                                    $capacityPerRoom = floor($total / $rooms);
                                                    $remainder = $total % $rooms;

                                                    if ($remainder === 0) {
                                                        return new HtmlString("<div style='padding: 0.75rem; background-color: #f0fdf4; border-radius: 0.375rem; border: 1px solid #86efac;'><span style='color: #15803d; font-weight: 500;'>✓ Perfect: {$capacityPerRoom} examinees per room</span></div>");
                                                    } else {
                                                        return new HtmlString("<div style='padding: 0.75rem; background-color: #fef2f2; border-radius: 0.375rem; border: 1px solid #fca5a5;'><span style='color: #dc2626; font-weight: 500;'>⚠ Uneven distribution - adjust values</span></div>");
                                                    }
                                                }),
                                ]),

                            // Step 4: Status & Confirmation
                            Wizard\Step::make('Confirmation')
                                ->icon('heroicon-o-check-circle')
                                ->description('Review and activate')
                                ->schema([
                                    Toggle::make('is_active')
                                                ->label('Active Slot')
                                                ->default(true)
                                                ->inline(false)
                                                ->helperText('When active, students can apply for this examination slot'),


                                ]),
                        ])
                            ->skippable(false),
                    ])->action(function (array $data) {

                        DB::beginTransaction();
                        try {
                            $examination = $this->getRecord();

                            // Validate date range
                            $examDate = \Carbon\Carbon::parse($data['date_of_exam']);
                            $startDate = \Carbon\Carbon::parse($examination->start_date);
                            $endDate = \Carbon\Carbon::parse($examination->end_date);

                            if ($examDate->lt($startDate) || $examDate->gt($endDate)) {
                                throw new \Exception('Examination date must be between '.$startDate->format('M d, Y').' and '.$endDate->format('M d, Y'));
                            }

                            // Validate even distribution
                            if ($data['total_examinees'] % $data['number_of_rooms'] !== 0) {
                                throw new \Exception('Total examinees must be evenly divisible by the number of rooms for fair distribution.');
                            }

                            // Create examination slot
                            $slot = ExaminationSlot::create([
                                'examination_id' => $examination->id,
                                'test_center_id' => $data['test_center_id'],
                                'building_name' => $data['building_name'],
                                'date_of_exam' => $data['date_of_exam'],
                                'total_examinees' => $data['total_examinees'],
                                'number_of_rooms' => $data['number_of_rooms'],
                                'is_active' => $data['is_active'],
                            ]);

                            // ✅ Auto-generate examination rooms (capacity is computed, not stored)
                            $rooms = [];

                            for ($i = 1; $i <= $data['number_of_rooms']; $i++) {
                                $rooms[] = [
                                    'examination_slot_id' => $slot->id,
                                    'room_number' => 'Room '.$i,
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

                    Action::make('view_rooms')
                        ->label('View Rooms')
                        ->icon('fontisto-room')
                        ->button()
                        ->color('primary')
                        ->modalHeading(fn ($record) => 'Rooms for ' . $record->building_name)
                        ->modalDescription(fn ($record) => $record->testCenter->name . ' - ' . \Carbon\Carbon::parse($record->date_of_exam)->format('M d, Y'))
                        ->modalSubmitAction(false)
                        ->modalCancelAction(fn ($action) => $action->label('Close'))
                        ->disabledForm()
                        ->modalContent(fn ($record): View => view(
                            'filament.resources.examinations.pages.examination-slot-rooms',
                            ['record' => $record],
                        ))
                        ->modalWidth(Width::Full),

                    DeleteAction::make()
                        ->disabled(fn (ExaminationSlot $record): bool =>
                            $record->rooms()->whereHas('applicationSlots')->exists()
                        )
                        ->tooltip(fn (ExaminationSlot $record): ?string =>
                            $record->rooms()->whereHas('applicationSlots')->exists()
                                ? 'Cannot delete: Students are already assigned to this slot'
                                : null
                        ),
            ])
            ->toolbarActions([
                // ...
            ]);
    }
}
