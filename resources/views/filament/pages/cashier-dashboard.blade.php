<div>
    <main class="flex-1 flex flex-col">
            <!-- TOP BAR -->
            <header class="px-6 py-4 border-b bg-white flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-slate-800">
                        Payment Verification
                    </h1>
                    <p class="text-xs text-slate-500">
                        Validate applicant payments for SKSU Tertiary Placement Test.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by name, Application ID, or Ref No."
                            class="w-72 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        />
                        <span class="absolute right-2 top-1.5 text-[10px] text-slate-400 uppercase">
                            Ctrl + K
                        </span>
                    </div>

                    <!-- User Badge -->
                    <button class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs hover:bg-slate-50">
                        <div class="h-7 w-7 rounded-full bg-emerald-500/90 flex items-center justify-center text-white text-xs font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <div class="font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-slate-500">Cashier</div>
                        </div>
                    </button>
                </div>
            </header>

            <!-- CONTENT -->
            <section class="flex-1 p-6 flex flex-col gap-4">
                <!-- METRICS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm px-4 py-3 border border-slate-100">
                        <div class="text-[11px] text-slate-500 uppercase tracking-wide">
                            Pending today
                        </div>
                        <div class="mt-1 flex items-baseline gap-2">
                            <div class="text-2xl font-semibold text-slate-900">{{ $this->pendingTodayCount }}</div>
                            <span class="text-[11px] text-amber-500 font-medium bg-amber-50 px-2 py-0.5 rounded-full">
                                Needs verification
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm px-4 py-3 border border-slate-100">
                        <div class="text-[11px] text-slate-500 uppercase tracking-wide">
                            Approved today
                        </div>
                        <div class="mt-1 flex items-baseline gap-2">
                            <div class="text-2xl font-semibold text-emerald-600">{{ $this->approvedTodayCount }}</div>
                            <span class="text-[11px] text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full">
                                Exam permit released
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm px-4 py-3 border border-slate-100">
                        <div class="text-[11px] text-slate-500 uppercase tracking-wide">
                            Rejected today
                        </div>
                        <div class="mt-1 flex items-baseline gap-2">
                            <div class="text-2xl font-semibold text-rose-500">{{ $this->rejectedTodayCount }}</div>
                            <span class="text-[11px] text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full">
                                Invalid proof
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm px-4 py-3 border border-slate-100">
                        <div class="text-[11px] text-slate-500 uppercase tracking-wide">
                            Total collected (today)
                        </div>
                        <div class="mt-1 flex items-baseline gap-2">
                            <div class="text-2xl font-semibold text-slate-900">₱{{ number_format($this->totalCollectedToday, 2) }}</div>
                            <span class="text-[11px] text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full">
                                Summary report
                            </span>
                        </div>
                    </div>
                </div>

                <!-- MAIN GRID: TABLE + DETAILS -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- LEFT: PENDING TABLE -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                        <!-- Table header -->
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold text-slate-800">
                                    Pending Payments
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    These applicants submitted payment but are not yet verified.
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <button
                                    wire:click="setFilter('all')"
                                    class="px-3 py-1.5 rounded-full border {{ $filter === 'all' ? 'border-emerald-200 text-emerald-600 bg-emerald-50' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                    All
                                </button>
                                <button
                                    wire:click="setFilter('today')"
                                    class="px-3 py-1.5 rounded-full border {{ $filter === 'today' ? 'border-emerald-200 text-emerald-600 bg-emerald-50' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                    Today
                                </button>
                                <button
                                    wire:click="setFilter('week')"
                                    class="px-3 py-1.5 rounded-full border {{ $filter === 'week' ? 'border-emerald-200 text-emerald-600 bg-emerald-50' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                    This Week
                                </button>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto flex-1">
                            <table class="min-w-full text-xs">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr class="text-[11px] text-slate-500 uppercase tracking-wide">
                                        <th class="px-4 py-2 text-left">Application ID</th>
                                        <th class="px-4 py-2 text-left">Applicant</th>
                                        <th class="px-4 py-2 text-left">Exam Slot</th>
                                        <th class="px-4 py-2 text-center">Amount</th>
                                        <th class="px-4 py-2 text-center">Status</th>
                                        <th class="px-4 py-2 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($this->pendingPayments as $payment)
                                        <tr
                                            wire:click="selectPayment({{ $payment->id }})"
                                            class="hover:bg-slate-50 cursor-pointer {{ $selectedPaymentId === $payment->id ? 'bg-emerald-50/60' : '' }}">
                                            <td class="px-4 py-3 align-top font-medium text-slate-800">
                                                {{ $payment->application?->examinee_number ?? 'N/A' }}
                                                <div class="text-[10px] text-slate-400">
                                                    Ref: {{ $payment->payment_reference ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="font-medium text-slate-800">
                                                    {{ $payment->applicant?->name ?? 'Unknown' }}
                                                </div>
                                                <div class="text-[10px] text-slate-500">
                                                    {{ $payment->application?->applicationInformation?->applicant_type ?? 'N/A' }} ·
                                                    {{ $payment->application?->firstPriorityProgram?->code ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top text-[11px]">
                                                @if($payment->application?->applicationSlot)
                                                    <div class="font-medium text-slate-800">
                                                        {{ $payment->application->applicationSlot->examinationSlot?->schedule_date?->format('M d, Y') ?? 'TBA' }} ·
                                                        {{ $payment->application->applicationSlot->examinationSlot?->start_time?->format('g:i A') ?? '' }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-500">
                                                        {{ $payment->application->applicationSlot->examinationSlot?->examinationRoom?->name ?? 'TBA' }}
                                                    </div>
                                                @else
                                                    <div class="text-[10px] text-slate-400">Not scheduled yet</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 align-top text-center text-sm font-semibold text-slate-900">
                                                ₱{{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="px-4 py-3 align-top text-center">
                                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-[10px] font-medium text-amber-600 border border-amber-100">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 align-top text-right">
                                                <button
                                                    wire:click.stop="selectPayment({{ $payment->id }})"
                                                    class="px-3 py-1.5 rounded-full {{ $selectedPaymentId === $payment->id ? 'bg-slate-900 text-slate-50' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }} text-[11px]">
                                                    View / Verify
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-slate-500 text-sm">
                                                No pending payments found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Table footer with pagination -->
                        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            <div>Showing {{ $this->pendingPayments->firstItem() ?? 0 }}–{{ $this->pendingPayments->lastItem() ?? 0 }} of {{ $this->pendingPayments->total() }} pending payments</div>
                            <div class="flex items-center gap-2">
                                @if ($this->pendingPayments->onFirstPage())
                                    <button disabled class="px-2 py-1 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">
                                        Prev
                                    </button>
                                @else
                                    <button wire:click="previousPage" class="px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50">
                                        Prev
                                    </button>
                                @endif

                                @foreach(range(1, $this->pendingPayments->lastPage()) as $page)
                                    @if($page == $this->pendingPayments->currentPage())
                                        <button class="px-2 py-1 rounded-lg bg-slate-900 text-slate-50">
                                            {{ $page }}
                                        </button>
                                    @elseif($page >= $this->pendingPayments->currentPage() - 2 && $page <= $this->pendingPayments->currentPage() + 2)
                                        <button wire:click="gotoPage({{ $page }})" class="px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endforeach

                                @if ($this->pendingPayments->hasMorePages())
                                    <button wire:click="nextPage" class="px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50">
                                        Next
                                    </button>
                                @else
                                    <button disabled class="px-2 py-1 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">
                                        Next
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: DETAIL PANEL -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                        @if($this->selectedPayment)
                            @php
                                $payment = $this->selectedPayment;
                                $application = $payment->application;
                                $info = $application?->applicationInformation;
                                $slot = $application?->applicationSlot;
                            @endphp

                            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-semibold text-slate-800">
                                        Selected Payment
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        Review information before approval.
                                    </div>
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    #{{ $application?->examinee_number ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="p-4 space-y-4 text-xs overflow-y-auto flex-1">
                                <!-- Applicant block -->
                                <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3 space-y-1">
                                    <div class="text-[11px] uppercase text-slate-500 font-medium">
                                        Applicant
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-semibold text-slate-900">
                                                {{ $payment->applicant?->name ?? 'Unknown' }}
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                {{ $info?->applicant_type ?? 'N/A' }} · {{ $application?->firstPriorityProgram?->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="text-right text-[10px] text-slate-500">
                                            Contact:
                                            <div class="font-medium text-slate-800">
                                                {{ $info?->contact_number ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Exam & seat -->
                                @if($slot)
                                    <div class="rounded-xl border border-slate-100 p-3 space-y-1">
                                        <div class="text-[11px] uppercase text-slate-500 font-medium">
                                            Examination Slot
                                        </div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $slot->examinationSlot?->schedule_date?->format('F d, Y') ?? 'TBA' }} ·
                                            {{ $slot->examinationSlot?->start_time?->format('g:i A') ?? '' }} –
                                            {{ $slot->examinationSlot?->end_time?->format('g:i A') ?? '' }}
                                        </div>
                                        <div class="text-[11px] text-slate-500">
                                            {{ $slot->examinationSlot?->examinationRoom?->testCenter?->name ?? 'TBA' }} ·
                                            {{ $slot->examinationSlot?->examinationRoom?->building ?? '' }} ·
                                            {{ $slot->examinationSlot?->examinationRoom?->name ?? 'TBA' }}
                                        </div>
                                        <div class="flex items-center justify-between mt-2 text-[11px]">
                                            <div class="text-slate-500">
                                                Seat Number:
                                                <span class="font-semibold text-slate-900">{{ $slot->seat_number ?? 'TBA' }}</span>
                                            </div>
                                            <div class="text-slate-500">
                                                Capacity:
                                                <span class="font-semibold text-slate-900">
                                                    {{ $slot->examinationSlot?->current_capacity ?? 0 }} / {{ $slot->examinationSlot?->max_capacity ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Payment block -->
                                <div class="rounded-xl border border-slate-100 p-3 space-y-2">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="text-[11px] uppercase text-slate-500 font-medium">
                                                Payment Details
                                            </div>
                                            <div class="text-sm font-semibold text-slate-900">
                                                ₱{{ number_format($payment->amount, 2) }}
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                Payment Type:
                                                <span class="font-medium text-slate-800">
                                                    {{ str_replace('_', ' ', $payment->payment_method) }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                Reference No:
                                                <span class="font-mono text-[11px] bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">
                                                    {{ $payment->payment_reference ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                Date Paid:
                                                <span class="font-medium text-slate-800">
                                                    {{ $payment->paid_at?->format('M d, Y · g:i A') ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right text-[11px] text-amber-600">
                                            Status:
                                            <div class="font-semibold">Pending</div>
                                        </div>
                                    </div>

                                    <!-- Placeholder for receipt image -->
                                    @if($payment->receipt_file)
                                        <div class="mt-2">
                                            <div class="text-[11px] text-slate-500 mb-1">
                                                Proof of Payment
                                            </div>
                                            <img src="{{ Storage::url($payment->receipt_file) }}" alt="Receipt" class="w-full rounded-xl border border-slate-200">
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <div class="text-[11px] text-slate-500 mb-1">
                                                Proof of Payment
                                            </div>
                                            <div class="aspect-video w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center text-[11px] text-slate-400">
                                                No receipt uploaded
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer actions -->
                            <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                                <button class="text-[11px] text-slate-500 hover:text-slate-700">
                                    View applicant profile
                                </button>
                                <div class="flex items-center gap-2">
                                    {{ ($this->rejectAction)(['payment' => $payment->id]) }}
                                    {{ ($this->approveAction)(['payment' => $payment->id]) }}
                                </div>
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center p-8">
                                <div class="text-center text-slate-400">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm">No payment selected</p>
                                    <p class="text-xs text-slate-400">Select a payment to view details</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SIMPLE HISTORY PREVIEW -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 text-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-800">
                                Recent Approved Payments
                            </div>
                            <div class="text-[11px] text-slate-500">
                                Quick glimpse of your last verifications.
                            </div>
                        </div>
                        <button class="text-[11px] text-emerald-600 hover:text-emerald-700">
                            View full history →
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @forelse($this->recentApproved as $approved)
                            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-[11px] text-slate-500">Application</div>
                                    <div class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        Approved
                                    </div>
                                </div>
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $approved->application?->examinee_number ?? 'N/A' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ $approved->applicant?->name ?? 'Unknown' }} · {{ $approved->application?->firstPriorityProgram?->code ?? 'N/A' }}
                                </div>
                                <div class="mt-2 text-[11px] text-slate-500">
                                    Amount:
                                    <span class="font-semibold text-slate-900">₱{{ number_format($approved->amount, 2) }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $approved->verified_at?->format('M d · g:i A') ?? 'N/A' }}
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center text-slate-400 py-4">
                                No approved payments today yet
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
    </main>

    <!-- Filament Actions Modals -->
    <x-filament-actions::modals />
</div>
