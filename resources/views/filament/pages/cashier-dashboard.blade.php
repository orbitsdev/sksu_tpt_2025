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
                    <!-- Create Application Button -->
                    {{ $this->createApplicationAction }}

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

                <!-- MAIN GRID: TABLE + RECENT APPROVED -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- LEFT: PENDING TABLE -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col min-h-[600px]">
                        <!-- Table header -->
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold text-slate-800">
                                    Pending Applications
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    Applications with payment pending verification.
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
                                    @forelse($this->pendingApplications as $application)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-3 align-top font-medium text-slate-800">
                                                {{ $application->examinee_number ?? 'N/A' }}
                                                <div class="text-[10px] text-slate-400">
                                                    Ref: {{ $application->payment?->payment_reference ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="font-medium text-slate-800">
                                                    {{ $application->user?->name ?? 'Unknown' }}
                                                </div>
                                                <div class="text-[10px] text-slate-500">
                                                    {{ $application->applicationInformation?->applicant_type ?? 'N/A' }} ·
                                                    {{ $application->firstPriorityProgram?->code ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top text-[11px]">
                                                @if($application->applicationSlot)
                                                    <div class="font-medium text-slate-800">
                                                        {{ $application->applicationSlot->examinationSlot?->schedule_date?->format('M d, Y') ?? 'TBA' }} ·
                                                        {{ $application->applicationSlot->examinationSlot?->start_time?->format('g:i A') ?? '' }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-500">
                                                        {{ $application->applicationSlot->examinationSlot?->examinationRoom?->name ?? 'TBA' }}
                                                    </div>
                                                @else
                                                    <div class="text-[10px] text-slate-400">Not scheduled yet</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 align-top text-center text-sm font-semibold text-slate-900">
                                                ₱{{ number_format($application->payment?->amount ?? 0, 2) }}
                                            </td>
                                            <td class="px-4 py-3 align-top text-center">
                                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-[10px] font-medium text-amber-600 border border-amber-100">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 align-top text-right">
                                                {{ ($this->viewAndVerifyAction)(['application' => $application->id]) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-slate-500 text-sm">
                                                No pending applications found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Table footer with pagination -->
                        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            <div>Showing {{ $this->pendingApplications->firstItem() ?? 0 }}–{{ $this->pendingApplications->lastItem() ?? 0 }} of {{ $this->pendingApplications->total() }} pending applications</div>
                            <div class="flex items-center gap-2">
                                @if ($this->pendingApplications->onFirstPage())
                                    <button disabled class="px-2 py-1 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">
                                        Prev
                                    </button>
                                @else
                                    <button wire:click="previousPage" class="px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50">
                                        Prev
                                    </button>
                                @endif

                                @foreach(range(1, $this->pendingApplications->lastPage()) as $page)
                                    @if($page == $this->pendingApplications->currentPage())
                                        <button class="px-2 py-1 rounded-lg bg-slate-900 text-slate-50">
                                            {{ $page }}
                                        </button>
                                    @elseif($page >= $this->pendingApplications->currentPage() - 2 && $page <= $this->pendingApplications->currentPage() + 2)
                                        <button wire:click="gotoPage({{ $page }})" class="px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endforeach

                                @if ($this->pendingApplications->hasMorePages())
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

                    <!-- RIGHT: RECENT APPROVED PAYMENTS -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <div class="text-sm font-semibold text-slate-800">
                                Recent Approved Payments
                            </div>
                            <div class="text-[11px] text-slate-500">
                                Your last verifications today
                            </div>
                        </div>

                        <div class="p-4 space-y-3 overflow-y-auto">
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
                                <div class="text-center text-slate-400 py-8">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-xs">No approved payments today yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
    </main>

    <!-- Filament Actions Modals -->
    <x-filament-actions::modals />
</div>
