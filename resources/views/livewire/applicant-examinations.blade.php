<div>
    {{-- Page Header --}}
    <flux:heading size="xl">Available Examinations</flux:heading>
    <flux:subheading>Browse and apply to examinations</flux:subheading>

    @role('student')
    <div class="mt-8">
        {{-- Type Filter Tabs --}}
        <div class="border-b border-neutral-200 dark:border-neutral-700">
            <nav class="-mb-px flex gap-6">
                <button wire:click="filterByType('all')"
                    class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors {{ $filterType === 'all' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white' : 'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                    All Examinations
                    <span class="ml-2 rounded-md bg-neutral-100 px-2 py-0.5 text-xs dark:bg-neutral-800">{{ $filterCounts['all'] }}</span>
                </button>
                <button wire:click="filterByType('active')"
                    class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors {{ $filterType === 'active' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white' : 'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                    Accepting Applications
                    <span class="ml-2 rounded-md bg-neutral-100 px-2 py-0.5 text-xs dark:bg-neutral-800">{{ $filterCounts['active'] }}</span>
                </button>
                <button wire:click="filterByType('upcoming')"
                    class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors {{ $filterType === 'upcoming' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white' : 'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                    Upcoming
                    <span class="ml-2 rounded-md bg-neutral-100 px-2 py-0.5 text-xs dark:bg-neutral-800">{{ $filterCounts['upcoming'] }}</span>
                </button>
            </nav>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="mt-4 rounded-md border border-neutral-300 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800">
                <p class="text-sm text-neutral-700 dark:text-neutral-300">{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mt-4 rounded-md border border-red-300 bg-red-50 p-4 dark:border-red-700 dark:bg-red-900/20">
                <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Examinations List --}}
        <div class="mt-6 space-y-4">
            @forelse($examinations as $exam)
                <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                        {{ $exam->title }}
                                    </h3>

                                    @if($exam->application_open)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                            <svg class="size-2 fill-green-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                            OPEN
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-md border border-neutral-300 bg-neutral-100 px-2 py-1 text-xs font-medium text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                            CLOSED
                                        </span>
                                    @endif

                                    @if($exam->has_applied)
                                        <span class="inline-flex items-center rounded-md border border-blue-300 bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:border-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                                            APPLIED
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">School Year:</span>
                                        <span class="ml-1 font-medium">{{ $exam->school_year }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">Type:</span>
                                        <span class="ml-1 font-medium">{{ ucfirst($exam->exam_type) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">Exam Date:</span>
                                        <span class="ml-1 font-medium">{{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">Slots:</span>
                                        <span class="ml-1 font-medium">{{ $exam->examination_slots_count }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 flex gap-3">
                                    <div class="rounded-md bg-neutral-100 px-3 py-2 dark:bg-neutral-800">
                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Total Capacity</div>
                                        <div class="mt-0.5 text-base font-semibold">{{ $exam->total_capacity }}</div>
                                    </div>
                                    <div class="rounded-md bg-neutral-100 px-3 py-2 dark:bg-neutral-800">
                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Available</div>
                                        <div class="mt-0.5 text-base font-semibold">{{ $exam->available_slots }}</div>
                                    </div>
                                    <div class="rounded-md bg-neutral-100 px-3 py-2 dark:bg-neutral-800">
                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Applicants</div>
                                        <div class="mt-0.5 text-base font-semibold">{{ $exam->applications_count }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                @if($exam->has_applied)
                                    <a href="{{ route('applicant.applications') }}"
                                        class="inline-flex items-center gap-2 rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700">
                                        View Application
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @elseif($exam->application_open && $exam->available_slots > 0)
                                    <button wire:click="startApplication({{ $exam->id }})"
                                        class="inline-flex items-center gap-2 rounded-md bg-sksu-green px-4 py-2 text-sm font-medium text-white hover:bg-sksu-green-light">
                                        Apply Now
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </button>
                                @elseif($exam->available_slots === 0)
                                    <button disabled
                                        class="inline-flex items-center gap-2 rounded-md border border-neutral-300 bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-400 cursor-not-allowed dark:border-neutral-700 dark:bg-neutral-800">
                                        Full
                                    </button>
                                @else
                                    <button disabled
                                        class="inline-flex items-center gap-2 rounded-md border border-neutral-300 bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-400 cursor-not-allowed dark:border-neutral-700 dark:bg-neutral-800">
                                        Applications Closed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
                    <div class="p-12 text-center">
                        <svg class="mx-auto size-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-zinc-900 dark:text-white">No examinations found</h3>
                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                            @if($filterType === 'all')
                                There are currently no examinations available.
                            @elseif($filterType === 'active')
                                There are no examinations accepting applications at this time.
                            @else
                                There are no upcoming examinations scheduled.
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($examinations->hasPages())
        <div class="mt-6">
            {{ $examinations->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="mt-8 rounded-xl border border-neutral-200 bg-white p-12 text-center dark:border-neutral-700 dark:bg-zinc-900">
        <svg class="mx-auto size-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <h3 class="mt-4 text-sm font-medium text-zinc-900 dark:text-white">Access Restricted</h3>
        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            You don't have permission to view examinations.
        </p>
    </div>
    @endrole
</div>
