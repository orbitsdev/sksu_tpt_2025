<div>
    {{-- Page Header with Welcome Banner --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-sksu-green to-sksu-green-light p-8 mb-8">
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex-1">
                <flux:heading size="xl" class="text-white">Welcome back, {{ auth()->user()->name }}!</flux:heading>
                <flux:subheading class="text-white/90 mt-2">Here's an overview of your examinations</flux:subheading>
            </div>
            <div class="hidden lg:block">
                <svg class="w-48 h-48" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Student with laptop illustration -->
                    <circle cx="100" cy="100" r="90" fill="#E6F200" opacity="0.2"/>
                    <rect x="60" y="90" width="80" height="50" rx="4" fill="white" opacity="0.9"/>
                    <rect x="65" y="95" width="70" height="30" fill="#063A15" opacity="0.1"/>
                    <circle cx="85" cy="60" r="15" fill="white" opacity="0.9"/>
                    <path d="M85 75 Q80 85, 70 85 Q60 85, 60 95 L60 100 L110 100 L110 95 Q110 85, 100 85 Q90 85, 85 75" fill="white" opacity="0.8"/>
                    <rect x="75" y="105" width="6" height="15" fill="#E6F200"/>
                    <rect x="85" y="105" width="6" height="15" fill="#E6F200"/>
                    <circle cx="135" cy="45" r="8" fill="#E6F200"/>
                    <circle cx="155" cy="55" r="6" fill="#E6F200" opacity="0.7"/>
                    <circle cx="145" cy="65" r="5" fill="#E6F200" opacity="0.5"/>
                </svg>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-sksu-gold opacity-10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-sksu-gold opacity-10 rounded-full -ml-24 -mb-24"></div>
    </div>

    @role('student')
    <div class="mt-8 grid gap-6">

        {{-- My Applications Section --}}
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <flux:heading size="lg">Your Applications</flux:heading>
            </div>
            <div class="p-6 space-y-4">
                @forelse($myApplications as $application)
                    <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-base font-semibold">{{ $application->examination->title }}</h4>
                                <div class="mt-2 flex items-center gap-4 text-sm">
                                    <span class="text-neutral-600 dark:text-neutral-400">
                                        Application #: <strong>{{ $application->exam_number }}</strong>
                                    </span>
                                    <span class="inline-flex items-center rounded-md border border-neutral-300 bg-neutral-100 px-2 py-1 text-xs font-medium dark:border-neutral-700 dark:bg-neutral-800">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-neutral-500">
                                    Applied on {{ $application->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <button wire:click="viewApplication({{ $application->id }})" class="inline-flex items-center rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-medium hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700">
                                View Details
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="mx-auto w-48 h-48 mb-6">
                            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="100" cy="100" r="80" fill="#E6F200" opacity="0.1"/>
                                <rect x="60" y="80" width="80" height="60" rx="4" fill="#063A15" opacity="0.1"/>
                                <rect x="70" y="90" width="60" height="40" fill="white" stroke="#063A15" stroke-width="2"/>
                                <line x1="75" y1="100" x2="110" y2="100" stroke="#E6F200" stroke-width="3"/>
                                <line x1="75" y1="110" x2="120" y2="110" stroke="#063A15" stroke-width="2" opacity="0.3"/>
                                <line x1="75" y1="120" x2="115" y2="120" stroke="#063A15" stroke-width="2" opacity="0.3"/>
                                <circle cx="145" cy="60" r="8" fill="#E6F200"/>
                                <circle cx="160" cy="70" r="6" fill="#063A15" opacity="0.3"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-sksu-green">No applications yet</h3>
                        <p class="mt-2 text-sm text-neutral-500">Get started by applying to an available examination below.</p>
                        <div class="mt-6">
                            <a href="{{ route('applicant.examinations') }}" class="inline-flex items-center gap-2 rounded-md bg-sksu-green px-4 py-2 text-sm font-medium text-white hover:bg-sksu-green-light">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Browse Examinations
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Available Examinations Section --}}
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <flux:heading size="lg">Available Examinations</flux:heading>
                <p class="mt-1 text-sm text-neutral-500">Applications are currently open for these examinations</p>
            </div>
            <div class="p-6 space-y-4">
                @forelse($activeExaminations as $exam)
                    <div class="rounded-lg border border-neutral-200 p-6 dark:border-neutral-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h4 class="text-lg font-semibold">{{ $exam->title }}</h4>
                                    <span class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                        <svg class="size-2 fill-green-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                        OPEN
                                    </span>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                                    <div>
                                        <span class="text-neutral-500">School Year:</span>
                                        <span class="ml-1 font-medium">{{ $exam->school_year }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500">Type:</span>
                                        <span class="ml-1 font-medium">{{ ucfirst($exam->type) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500">Exam Date:</span>
                                        <span class="ml-1 font-medium">{{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500">Slots:</span>
                                        <span class="ml-1 font-medium">{{ $exam->examination_slots_count }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 flex gap-3">
                                    <div class="rounded-md bg-neutral-100 px-3 py-2 dark:bg-neutral-800">
                                        <div class="text-xs text-neutral-500">Total Capacity</div>
                                        <div class="mt-0.5 text-base font-semibold">{{ $exam->total_capacity }}</div>
                                    </div>
                                    <div class="rounded-md bg-neutral-100 px-3 py-2 dark:bg-neutral-800">
                                        <div class="text-xs text-neutral-500">Available</div>
                                        <div class="mt-0.5 text-base font-semibold">{{ $exam->available_slots }}</div>
                                    </div>
                                    <div class="rounded-md bg-neutral-100 px-3 py-2 dark:bg-neutral-800">
                                        <div class="text-xs text-neutral-500">Applicants</div>
                                        <div class="mt-0.5 text-base font-semibold">{{ $exam->applications_count }}</div>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="startApplication({{ $exam->id }})" class="inline-flex items-center gap-2 rounded-md bg-sksu-green px-4 py-2 text-sm font-medium text-white hover:bg-sksu-green-light">
                                Apply Now
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto size-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium">No examinations available</h3>
                        <p class="mt-1 text-sm text-neutral-500">There are currently no examinations accepting applications.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Examinations Section --}}
        @if($upcomingExaminations->count() > 0)
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <flux:heading size="lg">Upcoming Examinations</flux:heading>
                <p class="mt-1 text-sm text-neutral-500">Applications will open soon</p>
            </div>
            <div class="p-6 space-y-4">
                @foreach($upcomingExaminations as $exam)
                    <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h4 class="text-base font-semibold">{{ $exam->title }}</h4>
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                        SOON
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center gap-4 text-sm">
                                    <span class="text-neutral-600 dark:text-neutral-400">
                                        Exam Date: <strong>{{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }}</strong>
                                    </span>
                                    <span class="text-neutral-600 dark:text-neutral-400">
                                        Slots: <strong>{{ $exam->examination_slots_count }}</strong>
                                    </span>
                                </div>
                            </div>
                            <button disabled class="inline-flex items-center rounded-md border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm font-medium text-neutral-400 cursor-not-allowed dark:border-neutral-700 dark:bg-neutral-800">
                                Applications Closed
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
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
            You don't have permission to access this dashboard.
        </p>
    </div>
    @endrole
</div>
