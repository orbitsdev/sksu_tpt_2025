<?php

namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\Application;
use App\Models\User;
use App\Models\PersonalInformation;
use App\Models\ApplicationInformation;
use App\Models\Program;
use App\Models\Examination;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use UnitEnum;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CashierDashboard extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    use WithPagination;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected string $view = 'filament.pages.cashier-dashboard';

    protected static string|UnitEnum|null $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 1;

    // Livewire properties
    public string $search = '';
    public string $filter = 'today'; // all, today, week

    public static function getNavigationLabel(): string
    {
        return 'Cashier Transaction';
    }

    public function getHeading(): string
    {
        return '';
    }

    public function mount(): void
    {
        //
    }

    // Real-time search
    public function updatedSearch(): void
    {
        $this->resetPage('page');
    }

    // Filter changes
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage('page');
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

    // Get applications with pending payments
    #[Computed]
    public function pendingApplications()
    {
        $query = Application::with([
            'user',
            'applicationInformation',
            'applicationSlot.examinationSlot.examinationRoom.testCenter',
            'firstPriorityProgram',
            'payment'
        ])
        ->whereHas('payment', function ($q) {
            $q->where('status', 'PENDING');
        });

        // Apply date filter (based on payment created_at)
        if ($this->filter === 'today') {
            $query->whereHas('payment', function ($q) {
                $q->whereDate('created_at', today());
            });
        } elseif ($this->filter === 'week') {
            $query->whereHas('payment', function ($q) {
                $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            });
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                })
                ->orWhere('examinee_number', 'like', "%{$this->search}%")
                ->orWhereHas('payment', function ($q) {
                    $q->where('payment_reference', 'like', "%{$this->search}%");
                });
            });
        }

        return $query->latest()->paginate(10);
    }

    // Recent approved payments
    #[Computed]
    public function recentApproved()
    {
        return Payment::with(['applicant', 'application.applicationInformation', 'application.firstPriorityProgram'])
            ->where('status', 'VERIFIED')
            ->whereDate('verified_at', today())
            ->latest('verified_at')
            ->limit(3)
            ->get();
    }

    // Create Application Wizard Action
    public function createApplicationAction(): Action
    {
        return Action::make('createApplication')
            ->label('Create New Application')
            ->icon('heroicon-o-plus-circle')
            ->color('success')
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
                                        ->options(Examination::pluck('title', 'id'))
                                        ->searchable()
                                        ->preload(),
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
                                            $amountPaid = (float) $get('amount_paid');
                                            if ($state && $amountPaid) {
                                                $set('change', max(0, $amountPaid - $state));
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
                                            $amount = (float) $get('amount');
                                            if ($state && $amount) {
                                                $set('change', max(0, $state - $amount));
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
                            Section::make('Payment Method')
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
                                        ->default('CASH'),
                                    TextInput::make('official_receipt_number')
                                        ->label('Official Receipt Number')
                                        ->required()
                                        ->unique('payments', 'official_receipt_number')
                                        ->maxLength(255),
                                ]),
                        ]),
                ])
                ->skippable(false),
            ])
            ->modalSubmitActionLabel('Create Application & Issue Permit')
            ->action(function (array $data) {
                try {
                    \DB::beginTransaction();

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
                        'status' => 'PENDING',
                        'step' => 1,
                        'step_description' => 'Payment Submitted',
                        'first_priority_program_id' => $data['first_priority_program_id'],
                        'second_priority_program_id' => $data['second_priority_program_id'],
                    ]);

                    // 4. Create Application Information
                    ApplicationInformation::create([
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

                    // 5. Create Payment (immediately verified for walk-in)
                    $payment = Payment::create([
                        'examination_id' => $data['examination_id'],
                        'applicant_id' => $user->id,
                        'application_id' => $application->id,
                        'cashier_id' => auth()->id(),
                        'amount' => $data['amount'],
                        'amount_paid' => $data['amount_paid'],
                        'change' => $data['change'],
                        'payment_method' => $data['payment_method'],
                        'official_receipt_number' => $data['official_receipt_number'],
                        'status' => 'VERIFIED',
                        'paid_at' => now(),
                        'verified_at' => now(),
                        'verified_by' => auth()->id(),
                    ]);

                    // 6. Generate Examinee Number and Issue Permit
                    $examineeNumber = 'SKSU-' . now()->year . '-' . str_pad($application->id, 6, '0', STR_PAD_LEFT);
                    $application->update([
                        'examinee_number' => $examineeNumber,
                        'permit_number' => $examineeNumber,
                        'permit_issued_at' => now(),
                        'status' => 'PERMIT_ISSUED',
                        'step' => 4,
                        'step_description' => 'Permit Issued',
                    ]);

                    \DB::commit();

                    Notification::make()
                        ->title('Application Created Successfully!')
                        ->body("Examinee Number: {$examineeNumber}")
                        ->success()
                        ->duration(10000)
                        ->send();

                    // Refresh data
                    unset($this->pendingApplications);

                } catch (\Exception $e) {
                    \DB::rollBack();

                    Notification::make()
                        ->title('Error Creating Application')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    // View & Verify Action (Main Modal)
    public function viewAndVerifyAction(): Action
    {
        return Action::make('viewAndVerify')
            ->label('View / Verify')
            ->icon('heroicon-o-eye')
            ->modalHeading(fn (array $arguments) => 'Application Details - #' . (Application::find($arguments['application'])?->examinee_number ?? 'N/A'))
            ->modalWidth('3xl')
            ->modalContent(fn (array $arguments) => view('filament.pages.partials.payment-details', [
                'payment' => Application::with([
                    'user',
                    'applicationInformation',
                    'applicationSlot.examinationSlot.examinationRoom.testCenter',
                    'firstPriorityProgram',
                    'payment'
                ])->find($arguments['application'])?->payment
            ]))
            ->modalFooterActions(fn (Action $action): array => [
                Action::make('reject')
                    ->label('Reject Payment')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
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
                    ->action(function (array $data, array $arguments) {
                        $application = Application::find($arguments['application']);
                        $payment = $application?->payment;

                        if (!$payment) {
                            Notification::make()
                                ->title('Payment not found')
                                ->danger()
                                ->send();
                            return;
                        }

                        $payment->update([
                            'status' => 'REJECTED',
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Payment Rejected')
                            ->body('The payment has been rejected. Applicant will be notified.')
                            ->warning()
                            ->send();

                        // Refresh data
                        $this->resetPage('page');
                        unset($this->pendingApplications);
                    }),
                Action::make('approve')
                    ->label('Approve & Generate Permit')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Payment')
                    ->modalDescription('Are you sure you want to approve this payment and generate the exam permit?')
                    ->form([
                        TextInput::make('official_receipt_number')
                            ->label('Official Receipt Number')
                            ->required()
                            ->maxLength(255)
                            ->unique('payments', 'official_receipt_number'),
                        Textarea::make('remarks')
                            ->label('Cashier Remarks (Optional)')
                            ->rows(3)
                            ->placeholder('E.g., Valid receipt, name matches ID, clear payment details.'),
                    ])
                    ->action(function (array $data, array $arguments) {
                        $application = Application::find($arguments['application']);
                        $payment = $application?->payment;

                        if (!$payment) {
                            Notification::make()
                                ->title('Payment not found')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Verify payment
                        $payment->update([
                            'official_receipt_number' => $data['official_receipt_number'],
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                            'status' => 'VERIFIED',
                        ]);

                        // Generate permit for application
                        if ($application && !$application->has_permit) {
                            $permitNumber = 'SKSU-' . now()->year . '-' . str_pad($application->id, 6, '0', STR_PAD_LEFT);
                            $application->issuePermit($permitNumber);
                        }

                        Notification::make()
                            ->title('Payment Approved!')
                            ->body('Exam permit has been generated successfully.')
                            ->success()
                            ->send();

                        // Refresh data
                        $this->resetPage('page');
                        unset($this->pendingApplications);
                    }),
            ])
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close')
            ->closeModalByClickingAway(false);
    }
}
