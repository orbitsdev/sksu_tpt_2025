<?php

namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\Application;
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
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

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
    public ?int $selectedPaymentId = null;

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
        // Select first payment by default if available
        $firstPayment = $this->pendingPayments->first();
        if ($firstPayment) {
            $this->selectedPaymentId = $firstPayment->id;
        }
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

    // Select payment
    public function selectPayment(int $paymentId): void
    {
        $this->selectedPaymentId = $paymentId;
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

    // Get pending payments with pagination
    #[Computed]
    public function pendingPayments()
    {
        $query = Payment::with(['applicant', 'application.applicationInformation', 'application.applicationSlot.examinationSlot.examinationRoom', 'application.firstPriorityProgram'])
            ->where('status', 'PENDING');

        // Apply date filter
        if ($this->filter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($this->filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('applicant', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                })
                ->orWhereHas('application', function ($q) {
                    $q->where('examinee_number', 'like', "%{$this->search}%");
                })
                ->orWhere('payment_reference', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    // Get selected payment details
    #[Computed]
    public function selectedPayment(): ?Payment
    {
        if (!$this->selectedPaymentId) {
            return null;
        }

        return Payment::with([
            'applicant',
            'application.applicationInformation',
            'application.applicationSlot.examinationSlot.examinationRoom.testCenter',
            'application.firstPriorityProgram'
        ])->find($this->selectedPaymentId);
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

    // Approve Action
    public function approveAction(): Action
    {
        return Action::make('approve')
            ->label('Approve & Generate Exam Permit')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Approve Payment')
            ->modalDescription('Are you sure you want to approve this payment and generate the exam permit?')
            ->schema([
                TextInput::make('official_receipt_number')
                    ->label('Official Receipt Number')
                    ->required()
                    ->maxLength(255)
                    ->unique('payments', 'official_receipt_number', ignoreRecord: true),
                Textarea::make('remarks')
                    ->label('Cashier Remarks (Optional)')
                    ->rows(3)
                    ->placeholder('E.g., Valid receipt, name matches ID, clear payment details.'),
            ])
            ->action(function (array $data, array $arguments) {
                $payment = Payment::find($arguments['payment']);

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
                $application = $payment->application;
                if ($application && !$application->has_permit) {
                    $permitNumber = 'SKSU-' . now()->year . '-' . str_pad($application->id, 6, '0', STR_PAD_LEFT);
                    $application->issuePermit($permitNumber);
                }

                Notification::make()
                    ->title('Payment Approved!')
                    ->body('Exam permit has been generated successfully.')
                    ->success()
                    ->send();

                // Refresh and select next payment
                $this->resetPage('page');
                unset($this->pendingPayments);
                $nextPayment = $this->pendingPayments->first();
                $this->selectedPaymentId = $nextPayment?->id;
            });
    }

    // Reject Action
    public function rejectAction(): Action
    {
        return Action::make('reject')
            ->label('Reject Payment')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Reject Payment')
            ->modalDescription('Please provide a reason for rejecting this payment.')
            ->schema([
                Textarea::make('reason')
                    ->label('Reason for Rejection')
                    ->required()
                    ->rows(4)
                    ->placeholder('E.g., Invalid receipt, mismatched details, unclear payment proof...'),
            ])
            ->action(function (array $data, array $arguments) {
                $payment = Payment::find($arguments['payment']);

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

                // Refresh and select next payment
                $this->resetPage('page');
                unset($this->pendingPayments);
                $nextPayment = $this->pendingPayments->first();
                $this->selectedPaymentId = $nextPayment?->id;
            });
    }
}
