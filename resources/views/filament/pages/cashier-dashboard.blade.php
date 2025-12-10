<div class="">
    <main class="flex-1 flex flex-col">
            <!-- TOP BAR -->
            <header class="px-6 py-4 border-b border-primary-600 bg-white  flex items-center justify-between  ">
                <div class="">
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
                            <div class="text-2xl font-semibold text-slate-900">₱{{ number_format((float)$this->totalCollectedToday, 2) }}</div>
                            <span class="text-[11px] text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full">
                                Summary report
                            </span>
                        </div>
                    </div>
                </div>

                <!-- MAIN GRID: TABLE + RECENT APPROVED -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- LEFT: FILAMENT TABLE -->
                    <div class="lg:col-span-2">
                        <div class="mb-4">
                            <x-filament::tabs>
                                @foreach ($this->getTabs() as $tabKey => $tab)
                                    <x-filament::tabs.item
                                        :active="$activeTab === $tabKey"
                                        wire:click="$set('activeTab', '{{ $tabKey }}')"
                                    >
                                        {{ $tab->getLabel() }}
                                        @if ($badge = $tab->getBadge())
                                            <x-filament::badge :color="$tab->getBadgeColor()">
                                                {{ $badge }}
                                            </x-filament::badge>
                                        @endif
                                    </x-filament::tabs.item>
                                @endforeach
                            </x-filament::tabs>
                        </div>
                        {{ $this->table }}
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
                                        {{ $approved->applicant?->personalInformation->getFullNameAttribute() ?? 'Unknown' }} · {{ $approved->application?->firstPriorityProgram?->code ?? 'N/A' }}
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
