<x-filament-panels::page>
    @php
        $exam = $this->record;
        $slotsCount = $exam->examinationSlots()->count();
        $applicantsCount = $exam->applications()->count();
        $totalCapacity = $exam->examinationSlots->flatMap(fn($slot) => $slot->rooms)->sum('capacity');
        $totalOccupied = $exam->examinationSlots->flatMap(fn($slot) => $slot->rooms)->sum('occupied');
        $totalAvailable = max($totalCapacity - $totalOccupied, 0);
    @endphp

    <div class="space-y-6">

        {{-- Overview Stats --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Slots Card -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-white/10 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Slots</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $slotsCount }}</p>
            </div>

            <!-- Applicants Card -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-white/10 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Applicants</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $applicantsCount }}</p>
            </div>

            <!-- Total Capacity Card -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-white/10 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Capacity</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalCapacity }}</p>
            </div>

            <!-- Available Seats Card -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-white/10 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Available</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalAvailable }}</p>
            </div>
        </div>

        {{-- Main Information Card --}}
        <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-white/10">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Examination Information</h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ $exam->title }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">School Year</dt>
                        <dd class="mt-1 text-base text-gray-900 dark:text-white">{{ $exam->school_year }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 dark:border-white/10 dark:bg-gray-800 dark:text-gray-300">
                                {{ ucfirst($exam->type) }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1 flex gap-2">
                            @if($exam->is_published)
                                <span class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 dark:border-white/20 dark:bg-gray-800 dark:text-gray-300">
                                    <x-filament::icon icon="heroicon-o-eye" class="mr-1 h-3 w-3" />
                                    Visible
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-medium text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                                    <x-filament::icon icon="heroicon-o-eye-slash" class="mr-1 h-3 w-3" />
                                    Hidden
                                </span>
                            @endif

                            @if($exam->is_application_open)
                                <span class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 dark:border-white/20 dark:bg-gray-800 dark:text-gray-300">
                                    <x-filament::icon icon="heroicon-o-check-circle" class="mr-1 h-3 w-3" />
                                    Accepting
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-medium text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                                    <x-filament::icon icon="heroicon-o-x-circle" class="mr-1 h-3 w-3" />
                                    Closed
                                </span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Date</dt>
                        <dd class="mt-1 text-base text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($exam->start_date)->format('F d, Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">End Date</dt>
                        <dd class="mt-1 text-base text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($exam->end_date)->format('F d, Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $exam->created_at->format('M d, Y h:i A') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $exam->updated_at->diffForHumans() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Examination Slots Section --}}
        <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-white/10">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Examination Slots ({{ $slotsCount }})</h2>
            </div>
            <div class="p-6">
                @forelse($exam->examinationSlots as $slot)
                    <div class="mb-4 last:mb-0 rounded-lg border border-gray-200 dark:border-white/10 p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $slot->building_name }}
                                    </h3>
                                    @if($slot->is_active)
                                        <span class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2 py-0.5 text-xs font-medium text-gray-700 dark:border-white/20 dark:bg-gray-800 dark:text-gray-300">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-2 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Campus:</span>
                                        <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $slot->testCenter->campus->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Test Center:</span>
                                        <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $slot->testCenter->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Date:</span>
                                        <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($slot->date_of_exam)->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Rooms:</span>
                                        <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $slot->number_of_rooms }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 grid grid-cols-3 gap-2 text-sm">
                                    <div class="rounded bg-gray-100/50 px-2.5 py-2 dark:bg-white/5">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Capacity</div>
                                        <div class="mt-0.5 font-semibold text-gray-900 dark:text-white">{{ $slot->rooms->sum('capacity') }}</div>
                                    </div>
                                    <div class="rounded bg-gray-100/50 px-2.5 py-2 dark:bg-white/5">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Occupied</div>
                                        <div class="mt-0.5 font-semibold text-gray-900 dark:text-white">{{ $slot->rooms->sum('occupied') }}</div>
                                    </div>
                                    <div class="rounded bg-gray-100/50 px-2.5 py-2 dark:bg-white/5">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Available</div>
                                        <div class="mt-0.5 font-semibold text-gray-900 dark:text-white">
                                            {{ max($slot->rooms->sum('capacity') - $slot->rooms->sum('occupied'), 0) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <x-filament::icon
                            icon="heroicon-o-calendar-days"
                            class="mx-auto h-12 w-12 text-gray-400"
                        />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No slots configured</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating an examination slot.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Capacity Utilization Progress --}}
        @if($totalCapacity > 0)
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Capacity Utilization</h2>
                </div>
                <div class="p-6">
                    @php
                        $utilizationPercent = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100) : 0;
                    @endphp
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $totalOccupied }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $totalCapacity }}</span> seats occupied
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $utilizationPercent }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                            <div
                                class="h-2.5 rounded-full transition-all duration-300
                                @if($utilizationPercent < 70)
                                    bg-emerald-500 dark:bg-emerald-400
                                @elseif($utilizationPercent < 90)
                                    bg-amber-500 dark:bg-amber-400
                                @else
                                    bg-rose-500 dark:bg-rose-400
                                @endif"
                                style="width: {{ min($utilizationPercent, 100) }}%;">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @if($utilizationPercent < 50)
                                Plenty of seats available for more applicants
                            @elseif($utilizationPercent < 80)
                                Moderate capacity usage
                            @elseif($utilizationPercent < 100)
                                Approaching full capacity
                            @else
                                At full capacity
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-filament-panels::page>
