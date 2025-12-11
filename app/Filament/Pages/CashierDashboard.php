<?php

namespace App\Filament\Pages;


use UnitEnum;
use BackedEnum;
use App\Models\User;
use App\Models\Payment;
use App\Models\Program;
use Filament\Pages\Page;

use Filament\Tables\Table;
use App\Models\Application;
use App\Models\Examination;
use Filament\Actions\Action;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalInformation;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Hash;
use App\Traits\InteractWithTabsTrait;


use Filament\Forms\Components\Select;

use App\Models\ApplicationInformation;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\HasTabs;

use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use emmanpbarrameda\FilamentTakePictureField\Forms\Components\TakePicture;
use Filament\Schemas\Components\Tabs\Tab;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Support\Enums\Width;
class CashierDashboard extends Page implements HasForms, HasActions, HasTable
{
    use HasTabs;
    use InteractsWithForms;
    use InteractsWithActions;
    use InteractWithTabsTrait;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected string $view = 'filament.pages.cashier-dashboard';

    protected static string|UnitEnum|null $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Cashier Transaction';
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(Payment::query()->count()),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'PENDING'))
                ->badge(Payment::where('status', 'PENDING')->count())
                ->badgeColor('warning'),
            'verified' => Tab::make('Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'VERIFIED'))
                ->badge(Payment::where('status', 'VERIFIED')->count())
                ->badgeColor('success'),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'REJECTED'))
                ->badge(Payment::where('status', 'REJECTED')->count())
                ->badgeColor('danger'),
        ];
    }

    // Computed properties for metrics
    #[Computed]
    public function pendingTodayCount(): int
    {
        return Payment::where('status', 'PENDING')
            ->whereDate('created_at', today())
            ->count();
    }

    #[Computed]
    public function approvedTodayCount(): int
    {
        return Payment::where('status', 'VERIFIED')
            ->whereDate('verified_at', today())
            ->count();
    }

    #[Computed]
    public function rejectedTodayCount(): int
    {
        return Payment::where('status', 'REJECTED')
            ->whereDate('updated_at', today())
            ->count();
    }

    #[Computed]
    public function totalCollectedToday(): float
    {
        return Payment::where('status', 'VERIFIED')
            ->whereDate('verified_at', today())
            ->sum('amount');
    }

    // Filament Table
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->with([
                        'applicant',
                        'application.applicationInformation',
                        'application.firstPriorityProgram',
                        'application.media'
                    ])
            )
            ->columns([
                SpatieMediaLibraryImageColumn::make('application.photo')
                    ->label('')
                    ->collection('photo')
                    ->circular()
                    ->width(40)
                    ->height(40)
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('applicant.name')
                    ->label('Applicant Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->sortable()
                    ->weight('medium'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'PENDING',
                        'success' => 'VERIFIED',
                        'danger' => 'REJECTED',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'PENDING',
                        'heroicon-o-check-circle' => 'VERIFIED',
                        'heroicon-o-x-circle' => 'REJECTED',
                    ]),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y g:i A')
                    ->sortable(),

                // Toggleable columns (hidden by default to save space)
                TextColumn::make('application.examinee_number')
                    ->label('Examinee No.')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->placeholder('Not Assigned')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('payment_reference')
                    ->label('Payment Ref')
                    ->searchable()
                    ->placeholder('No Reference')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'PENDING' => 'Pending',
                        'VERIFIED' => 'Verified',
                        'REJECTED' => 'Rejected',
                    ]),

                Filter::make('created_today')
                    ->label('Today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today())),

                Filter::make('created_this_week')
                    ->label('This Week')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),

                SelectFilter::make('application.applicationInformation.type')
                    ->label('Applicant Type')
                    ->options([
                        'Freshmen' => 'Freshmen',
                        'Transferee' => 'Transferee',
                    ]),
            ])
            ->actions([
                Action::make('viewAndVerify')
                       ->modalWidth(Width::SevenExtraLarge)
                    ->label(function (Payment $record) {
                        return match($record->status) {
                            'VERIFIED' => 'View Details',
                            'REJECTED' => 'View Details',
                            default => 'View / Verify'
                        };
                    })
                    ->icon('heroicon-o-eye')
                    ->button()
                    ->color(function (Payment $record) {
                        return match($record->status) {
                            'VERIFIED' => 'primary',
                            'REJECTED' => 'danger',
                            default => 'primary'
                        };
                    })
                    ->modalHeading(fn (Payment $record) => 'Payment Details - #' . ($record->application?->examinee_number ?? 'Not Assigned'))


                    ->modalContent(fn (Payment $record) => view('filament.pages.partials.payment-details', [
                        'payment' => $record
                    ]))
                    ->modalFooterActions(fn (Action $action): array => [
                        Action::make('reject')
                            ->label('Reject Payment')
                            ->icon('heroicon-o-x-circle')
                            ->color('danger')
                            ->visible(fn (Payment $record) => $record->status === 'PENDING')
                            ->requiresConfirmation()
                            ->modalHeading('Reject Payment')
                            ->modalDescription('Please provide a reason for rejecting this payment.')
                            ->form([
                                Textarea::make('reason')
                                    ->label('Reason for Rejection')
                                    ->required()
                                    ->rows(4)
                                    ->placeholder('E.g., Invalid receipt, mismatched details, unclear payment proof...'),
                            ])
                            ->action(function (array $data, Payment $record) {
                                $record->update([
                                    'status' => 'REJECTED',
                                    'rejection_reason' => $data['reason'],
                                    'verified_by' => auth()->id(),
                                    'verified_at' => now(),
                                ]);

                                Notification::make()
                                    ->title('Payment Rejected')
                                    ->body('The payment has been rejected. Applicant will be notified.')
                                    ->warning()
                                    ->send();

                                // Close all modals
                                $this->mountedActionsData = [];
                                $this->mountedActions = [];
                            }),

                        Action::make('approve')
                            ->label('Approve')
                            ->icon('heroicon-o-check-circle')
                            ->color('primary')
                            ->visible(fn (Payment $record) => $record->status === 'PENDING')
                            ->requiresConfirmation()
                            ->modalHeading('Approve Payment')
                            ->modalDescription('Are you sure you want to approve this payment and generate the exam permit?')
                            ->action(function (Payment $record) {
                                // Verify payment
                                $record->update([
                                    'verified_by' => auth()->id(),
                                    'verified_at' => now(),
                                    'status' => 'VERIFIED',
                                ]);

                                // Generate permit for application
                                $application = $record->application;
                                if ($application && !$application->has_permit) {
                                    $permitNumber = $application->id;
                                    $application->issuePermit((string) $permitNumber);
                                }

                                Notification::make()
                                    ->title('Payment Approved!')
                                    ->body('Exam permit has been generated successfully.')
                                    ->success()
                                    ->send();

                                // Close all modals
                                $this->mountedActionsData = [];
                                $this->mountedActions = [];
                            }),

                        Action::make('updateOR')
                            ->label('Add/Update OR & Receipt')
                            ->icon('heroicon-o-document-text')
                            ->color('info')
                            ->visible(fn (Payment $record) => in_array($record->status, ['PENDING', 'VERIFIED']))
                            ->form([
                                TextInput::make('official_receipt_number')
                                    ->label('Official Receipt Number')
                                    ->required()
                                    ->maxLength(255)
                                    ->default(fn (Payment $record) => $record->official_receipt_number),
                                SpatieMediaLibraryFileUpload::make('receipt')
                                    ->label('Attach Receipt (Optional)')
                                    ->collection('receipt')
                                    ->image()
                                    ->maxSize(5120)
                                    ->helperText('Upload or update receipt image'),
                            ])
                            ->action(function (array $data, Payment $record) {
                                // Check if OR number already exists
                                if (!empty($data['official_receipt_number'])) {
                                    $existingPayment = Payment::where('official_receipt_number', $data['official_receipt_number'])
                                        ->where('id', '!=', $record->id)
                                        ->first();

                                    if ($existingPayment) {
                                        Notification::make()
                                            ->title('Duplicate OR Number')
                                            ->body('This Official Receipt Number is already used.')
                                            ->danger()
                                            ->send();
                                        return;
                                    }
                                }

                                $record->update([
                                    'official_receipt_number' => $data['official_receipt_number'],
                                ]);

                                Notification::make()
                                    ->title('OR Number Updated!')
                                    ->success()
                                    ->send();

                                // Close all modals
                                $this->mountedActionsData = [];
                                $this->mountedActions = [];
                            }),

                        Action::make('assignSlot')
                            ->label(function (Payment $record) {
                                $hasSlot = $record->application?->applicationSlot;
                                return $hasSlot ? 'Update Exam Slot' : 'Assign Exam Slot';
                            })
                            ->icon('heroicon-o-calendar')
                            ->color('success')
                            ->visible(fn (Payment $record) => $record->status === 'VERIFIED')
                            ->form([
                                Select::make('examination_slot_id')
                                    ->label('Exam Slot')
                                    ->required()
                                    ->options(function (Payment $record) {
                                        $examinationId = $record->application?->examination_id;
                                        if (!$examinationId) {
                                            return [];
                                        }

                                        $slots = \App\Models\ExaminationSlot::where('examination_id', $examinationId)
                                            ->where('is_active', true)
                                            ->where('total_examinees', '>', 0)
                                            ->get()
                                            ->filter(function ($slot) {
                                                return $slot->assigned_students_count < $slot->total_examinees;
                                            });

                                        if ($slots->isEmpty()) {
                                            return [];
                                        }

                                        return $slots->mapWithKeys(function ($slot) {
                                            $available = $slot->total_examinees - $slot->assigned_students_count;
                                            return [
                                                $slot->id => sprintf(
                                                    '%s (%d/%d seats available)',
                                                    $slot->date_of_exam?->format('M d, Y'),
                                                    $available,
                                                    $slot->total_examinees
                                                )
                                            ];
                                        })->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->helperText(fn (Payment $record) =>
                                        !$record->application?->examination_id
                                            ? 'No examination assigned to this application.'
                                            : 'Select an available exam slot.'
                                    )
                                    ->placeholder('No available slots')
                                    ->default(fn (Payment $record) => $record->application?->applicationSlot?->examination_slot_id),

                                Select::make('examination_room_id')
                                    ->label('Exam Room (Optional)')
                                    ->options(function (Get $get, Payment $record) {
                                        $slotId = $get('examination_slot_id');
                                        if (!$slotId) {
                                            return [];
                                        }

                                        $slot = \App\Models\ExaminationSlot::find($slotId);
                                        if (!$slot) {
                                            return [];
                                        }

                                        return $slot->rooms()
                                            ->get()
                                            ->mapWithKeys(function ($room) {
                                                return [
                                                    $room->id => sprintf(
                                                        '%s (Capacity: %d, Available: %d)',
                                                        $room->room_number,
                                                        $room->capacity,
                                                        $room->available
                                                    )
                                                ];
                                            })
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->visible(fn (Get $get) => !empty($get('examination_slot_id')))
                                    ->default(fn (Payment $record) => $record->application?->applicationSlot?->examination_room_id),

                                TextInput::make('seat_number')
                                    ->label('Seat Number (Optional)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->visible(fn (Get $get) => !empty($get('examination_slot_id')))
                                    ->default(fn (Payment $record) => $record->application?->applicationSlot?->seat_number),
                            ])
                            ->action(function (array $data, Payment $record) {
                                $application = $record->application;
                                if (!$application) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Application not found.')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                // Update or create application slot
                                $applicationSlot = $application->applicationSlot;
                                if ($applicationSlot) {
                                    $applicationSlot->update([
                                        'examination_slot_id' => $data['examination_slot_id'],
                                        'examination_room_id' => $data['examination_room_id'] ?? null,
                                        'seat_number' => $data['seat_number'] ?? null,
                                    ]);
                                } else {
                                    \App\Models\ApplicationSlot::create([
                                        'application_id' => $application->id,
                                        'examination_slot_id' => $data['examination_slot_id'],
                                        'examination_room_id' => $data['examination_room_id'] ?? null,
                                        'seat_number' => $data['seat_number'] ?? null,
                                    ]);
                                }

                                // Update application step if needed
                                if ($application->current_step < 80) {
                                    $application->update([
                                        'current_step' => 80,
                                        'step_description' => 'Slot Assigned & Waiting for Exam Day',
                                    ]);
                                }

                                Notification::make()
                                    ->title('Exam Slot Assigned!')
                                    ->body('Examination slot has been successfully assigned.')
                                    ->success()
                                    ->send();

                                // Close all modals
                                $this->mountedActionsData = [];
                                $this->mountedActions = [];
                            }),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->closeModalByClickingAway(false),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    // Recent approved payments
    #[Computed]
    public function recentApproved()
    {
        return Payment::with(['applicant', 'application.applicationInformation', 'application.firstPriorityProgram'])
            ->where('status', 'VERIFIED')
            ->whereDate('verified_at', today())
            ->latest('verified_at')
            ->limit(5)
            ->get();
    }

    // Create Application Wizard Action
    public function createApplicationAction(): Action
    {
        return Action::make('createApplication')
            ->label('Create New Application')
            ->icon('heroicon-o-plus-circle')
            ->color('primary')
            ->extraAttributes(['class' => 'font-bold'])
            ->modalWidth('7xl')
            ->slideOver()
            ->schema([
                Wizard::make([
                    // Step 1: User Account
                    Wizard\Step::make('User Account')
                        ->icon('heroicon-o-user')
                        ->schema([
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique('users', 'email')
                                ->maxLength(255),
                            TextInput::make('password')
                                ->password()
                                ->required()
                                ->minLength(8)
                                ->maxLength(255)
                                ->dehydrated(fn ($state) => filled($state))
                                ->revealable(),
                            TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state) {
                                    if (!$state) return;
                                    $parts = explode(' ', $state);
                                    if (count($parts) >= 2) {
                                        $set('first_name', $parts[0]);
                                        $set('last_name', end($parts));
                                        if (count($parts) > 2) {
                                            $middle = array_slice($parts, 1, -1);
                                            $set('middle_name', implode(' ', $middle));
                                        }
                                    }
                                }),
                        ]),

                    // Step 2: Personal Information
                    Wizard\Step::make('Personal Information')
                        ->icon('heroicon-o-identification')
                        ->schema([
                            Section::make('Student Photo')
                                ->schema([
                                    // TakePicture::make('photo')
                                    //     ->label('Take Student Photo')
                                    //     ->disk('public')
                                    //     ->directory('student-photos')
                                    //     ->visibility('public')
                                    //     ->useModal(true)
                                    //     ->showCameraSelector(true)
                                    //     ->aspect('1:1')
                                    //     ->imageQuality(85)
                                    //     ->helperText('Click to take photo using camera or upload existing photo'),

                                    SpatieMediaLibraryFileUpload::make('photo')
                                        ->label('Student Photo')
                                        ->collection('photo')
                                        ->image()
                                        ->imageEditor()
                                        ->imageEditorAspectRatios([
                                            '1:1',
                                            '4:3',
                                            '16:9',
                                        ])
                                        ->maxSize(5120)
                                        ->helperText('Upload student photo (Max 5MB)'),
                                ]),
                            Section::make('Basic Information')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('first_name')
                                        ->label('First Name')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('middle_name')
                                        ->label('Middle Name')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('last_name')
                                        ->label('Last Name')
                                        ->required()
                                        ->maxLength(255),
                                    Select::make('suffix')
                                        ->label('Suffix')
                                        ->options([
                                            'Jr.' => 'Jr.',
                                            'Sr.' => 'Sr.',
                                            'II' => 'II',
                                            'III' => 'III',
                                            'IV' => 'IV',
                                            'V' => 'V',
                                        ])
                                        ->placeholder('Select suffix (optional)')
                                        ->native(false)
                                        ->searchable(),
                                    Select::make('sex')
                                        ->label('Sex')
                                        ->required()
                                        ->options([
                                            'Male' => 'Male',
                                            'Female' => 'Female',
                                        ]),
                                    DatePicker::make('birth_date')
                                        ->label('Date of Birth')
                                        ->native(false)
                                        ->required()
                                        ->maxDate(now()->subYears(15))
                                        ->displayFormat('F d, Y'),
                                ]),
                            Section::make('Contact Information')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('contact_number')
                                        ->label('Contact Number')
                                        ->required()
                                        ->mask('99999999999')
                                        ->length(11),
                                    TextInput::make('personal_email')
                                        ->label('Personal Email (Optional)')
                                        ->email()
                                        ->maxLength(255),
                                ]),
                        ]),

                    // Step 3: Application Information
                    Wizard\Step::make('Application Information')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Application Type')
                                ->columns(2)
                                ->schema([
                                    Select::make('examination_id')
                                        ->label('Examination')
                                        ->required()
                                        ->options(function () {
                                            return Examination::where('application_open', true)
                                                ->where('is_public', true)
                                                ->whereHas('examinationSlots', function ($query) {
                                                    $query->where('is_active', true)
                                                        ->where('total_examinees', '>', 0);
                                                })
                                                ->get()
                                                ->filter(function ($exam) {
                                                    // Only show exams with at least one slot that has availability
                                                    return $exam->examinationSlots()
                                                        ->where('is_active', true)
                                                        ->where('total_examinees', '>', 0)
                                                        ->get()
                                                        ->filter(function ($slot) {
                                                            return $slot->assigned_students_count < $slot->total_examinees;
                                                        })
                                                        ->count() > 0;
                                                })
                                                ->mapWithKeys(function ($exam) {
                                                    $availableSlots = $exam->examinationSlots()
                                                        ->where('is_active', true)
                                                        ->where('total_examinees', '>', 0)
                                                        ->get()
                                                        ->filter(function ($slot) {
                                                            return $slot->assigned_students_count < $slot->total_examinees;
                                                        })
                                                        ->count();

                                                    $totalAvailableSeats = $exam->examinationSlots()
                                                        ->where('is_active', true)
                                                        ->where('total_examinees', '>', 0)
                                                        ->get()
                                                        ->sum(function ($slot) {
                                                            return max(0, $slot->total_examinees - $slot->assigned_students_count);
                                                        });

                                                    return [
                                                        $exam->id => sprintf(
                                                            '%s (%s) - %d slots (%d seats available)',
                                                            $exam->title,
                                                            $exam->school_year,
                                                            $availableSlots,
                                                            $totalAvailableSeats
                                                        )
                                                    ];
                                                })
                                                ->toArray();
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Only examinations with open applications and available slots are shown'),
                                    Select::make('applicant_type')
                                        ->label('Applicant Type')
                                        ->required()
                                        ->options([
                                            'Freshmen' => 'Freshmen',
                                            'Transferee' => 'Transferee',
                                        ])
                                        ->live(),
                                ]),
                            Section::make('Program Choices')
                                ->columns(2)
                                ->schema([
                                    Select::make('first_priority_program_id')
                                        ->label('First Priority Program')
                                        ->required()
                                        ->options(Program::where('is_offered', true)->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->live(),
                                    Select::make('second_priority_program_id')
                                        ->label('Second Priority Program')
                                        ->required()
                                        ->options(function (Get $get) {
                                            $firstPriority = $get('first_priority_program_id');
                                            return Program::where('is_offered', true)
                                                ->when($firstPriority, fn($query) => $query->where('id', '!=', $firstPriority))
                                                ->pluck('name', 'id');
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Must be different from first priority'),
                                ]),
                            Section::make('Educational Background')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('school_graduated')
                                        ->label('School Graduated From')
                                        ->maxLength(255),
                                    TextInput::make('year_graduated')
                                        ->label('Year Graduated')
                                        ->numeric()
                                        ->minValue(1950)
                                        ->maxValue(now()->year),
                                    TextInput::make('track_and_strand_taken')
                                        ->label('Track & Strand Taken (SHS)')
                                        ->maxLength(255)
                                        ->visible(fn (Get $get) => $get('applicant_type') === 'Freshmen'),
                                ]),
                        ]),

                    // Step 4: Payment
                    Wizard\Step::make('Payment')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make('Payment Details')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('amount')
                                        ->label('Amount Due')
                                        ->required()
                                        ->numeric()
                                        ->prefix('₱')
                                        ->default(350)
                                        ->minValue(0)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, Get $get, ?float $state) {
                                            $amountPaid = (float) ($get('amount_paid') ?? 0);
                                            $amount = (float) ($state ?? 0);
                                            if ($amountPaid > 0 && $amount > 0) {
                                                $set('change', max(0, $amountPaid - $amount));
                                            }
                                        }),
                                    TextInput::make('amount_paid')
                                        ->label('Amount Paid')
                                        ->required()
                                        ->numeric()
                                        ->prefix('₱')
                                        ->minValue(0)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, Get $get, ?float $state) {
                                            $amount = (float) ($get('amount') ?? 0);
                                            $amountPaid = (float) ($state ?? 0);
                                            if ($amountPaid > 0 && $amount > 0) {
                                                $set('change', max(0, $amountPaid - $amount));
                                            }
                                        }),
                                    TextInput::make('change')
                                        ->label('Change')
                                        ->numeric()
                                        ->prefix('₱')
                                        ->readOnly()
                                        ->default(0)
                                        ->dehydrated(true),
                                ]),
                            Section::make('Payment Method & Reference')
                                ->columns(2)
                                ->schema([
                                    Select::make('payment_method')
                                        ->label('Payment Method')
                                        ->required()
                                        ->options([
                                            'CASH' => 'Cash',
                                            'GCASH' => 'GCash',
                                            'BANK_TRANSFER' => 'Bank Transfer',
                                        ])
                                        ->default('CASH')
                                        ->live(),
                                    TextInput::make('payment_reference')
                                        ->label('Payment Reference Number (Optional)')
                                        ->maxLength(255)
                                        ->visible(fn (Get $get) => in_array($get('payment_method'), ['GCASH', 'BANK_TRANSFER']))
                                        ->placeholder('Enter reference number if available')
                                        ->helperText('GCash reference, bank confirmation number, or transaction ID'),
                                ]),
                            Section::make('Proof of Payment')
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('receipt')
                                        ->label('Receipt/Proof of Payment (Optional)')
                                        ->collection('receipt')
                                        ->image()
                                        ->imageEditor()
                                        ->imageEditorAspectRatios([
                                            null,
                                            '16:9',
                                            '4:3',
                                            '1:1',
                                        ])
                                        ->maxSize(5120)
                                        ->helperText('Upload receipt image if available (Max 5MB)'),
                                ]),
                        ]),
                ])
                ->skippable(false),
            ])
            ->modalSubmitActionLabel('Submit Application for Verification')
            ->action(function (array $data) {
                try {
                    DB::beginTransaction();

                    // 1. Create User
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                    ]);

                    // 2. Create Personal Information
                    PersonalInformation::create([
                        'user_id' => $user->id,
                        'first_name' => $data['first_name'],
                        'middle_name' => $data['middle_name'] ?? null,
                        'last_name' => $data['last_name'],
                        'suffix' => $data['suffix'] ?? null,
                        'sex' => $data['sex'],
                        'birth_date' => $data['birth_date'],
                        'email' => $data['personal_email'] ?? $data['email'],
                        'contact_number' => $data['contact_number'],
                    ]);

                    // 3. Create Application
                    $application = Application::create([
                        'examination_id' => $data['examination_id'],
                        'user_id' => $user->id,
                        'current_step' => 59,
                        'step_description' => 'Submitted for Verification (Pending)',
                        'first_priority_program_id' => $data['first_priority_program_id'],
                        'second_priority_program_id' => $data['second_priority_program_id'],
                    ]);

                    // 4. Create Application Information
                    $applicationInfo = ApplicationInformation::create([
                        'application_id' => $application->id,
                        'type' => $data['applicant_type'],
                        'first_name' => $data['first_name'],
                        'middle_name' => $data['middle_name'] ?? null,
                        'last_name' => $data['last_name'],
                        'extension' => $data['suffix'] ?? null,
                        'sex' => $data['sex'],
                        'date_of_birth' => $data['birth_date'],
                        'contact_number' => $data['contact_number'],
                        'school_graduated' => $data['school_graduated'] ?? null,
                        'year_graduated' => $data['year_graduated'] ?? null,
                        'track_and_strand_taken' => $data['track_and_strand_taken'] ?? null,
                    ]);

                    // 4b. Attach photo to Application
                    // With wizard + custom action, we need to handle media manually
                    // The SpatieMediaLibraryFileUpload stores data differently
                    // For now, photos will be handled when creating application

                    // 5. Create Payment (PENDING - needs verification in table)
                    $payment = Payment::create([
                        'examination_id' => $data['examination_id'],
                        'applicant_id' => $user->id,
                        'application_id' => $application->id,
                        'cashier_id' => auth()->id(),
                        'amount' => $data['amount'],
                        'amount_paid' => $data['amount_paid'],
                        'change' => $data['change'],
                        'payment_method' => $data['payment_method'],
                        'payment_reference' => $data['payment_reference'] ?? null,
                        'status' => 'PENDING',
                        'paid_at' => now(),
                    ]);

                    // 5b. Attach receipt to Payment
                    // With wizard + custom action, media handled by plugin

                    DB::commit();

                    Notification::make()
                        ->title('Application Created Successfully!')
                        ->body('Application is now pending verification in the table below.')
                        ->success()
                        ->duration(10000)
                        ->send();

                    // Refresh data
                    unset($this->pendingApplications);

                } catch (\Exception $e) {
                    DBS::rollBack();

                    Notification::make()
                        ->title('Error Creating Application')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
