<div>
    {{-- Page Header with Back Button --}}
    <div class="mb-6 flex items-center gap-4">
        <button wire:click="goBack" class="inline-flex items-center gap-2 text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Applications
        </button>
    </div>

    <flux:heading size="xl">Application Details</flux:heading>
    <flux:subheading>View your application information</flux:subheading>

    @role('student')
    <div class="mt-8 space-y-6">

        {{-- Application Status Card --}}
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800/50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Application Status</h3>
                    <span class="inline-flex items-center rounded-md border px-3 py-1.5 text-sm font-medium
                        @if($application->status === 'pending') border-neutral-300 bg-neutral-100 text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300
                        @elseif($application->status === 'approved') border-green-300 bg-green-50 text-green-700 dark:border-green-700 dark:bg-green-900/20 dark:text-green-400
                        @elseif($application->status === 'rejected') border-red-300 bg-red-50 text-red-700 dark:border-red-700 dark:bg-red-900/20 dark:text-red-400
                        @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Application Number</label>
                        <p class="mt-1 text-base font-semibold text-zinc-900 dark:text-white">{{ $application->exam_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Application Date</label>
                        <p class="mt-1 text-base font-semibold text-zinc-900 dark:text-white">{{ $application->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Last Updated</label>
                        <p class="mt-1 text-base font-semibold text-zinc-900 dark:text-white">{{ $application->updated_at->format('F d, Y g:i A') }}</p>
                    </div>
                    @if($application->status === 'approved')
                    <div>
                        <label class="text-sm font-medium text-green-600 dark:text-green-400">Approval Date</label>
                        <p class="mt-1 text-base font-semibold text-green-700 dark:text-green-300">{{ $application->updated_at->format('F d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Examination Details Card --}}
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Examination Information</h3>
            </div>
            <div class="p-6">
                <h4 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $application->examination->title }}</h4>

                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-neutral-500 dark:text-neutral-400">School Year</label>
                        <p class="mt-1 text-base font-semibold text-zinc-900 dark:text-white">{{ $application->examination->school_year }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Type</label>
                        <p class="mt-1 text-base font-semibold text-zinc-900 dark:text-white">{{ ucfirst($application->examination->type) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Examination Date</label>
                        <p class="mt-1 text-base font-semibold text-zinc-900 dark:text-white">{{ \Carbon\Carbon::parse($application->examination->start_date)->format('F d, Y') }}</p>
                    </div>
                </div>

                @if($application->examination->description)
                <div class="mt-6">
                    <label class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Description</label>
                    <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-300">{{ $application->examination->description }}</p>
                </div>
                @endif

                <div class="mt-6 flex gap-3">
                    <div class="flex-1 rounded-md bg-neutral-100 px-4 py-3 dark:bg-neutral-800">
                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Total Slots</div>
                        <div class="mt-1 text-lg font-semibold">{{ $application->examination->examinationSlots->count() }}</div>
                    </div>
                    <div class="flex-1 rounded-md bg-neutral-100 px-4 py-3 dark:bg-neutral-800">
                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Total Capacity</div>
                        <div class="mt-1 text-lg font-semibold">{{ $application->examination->examinationSlots->flatMap(fn($slot) => $slot->rooms)->sum('capacity') }}</div>
                    </div>
                    <div class="flex-1 rounded-md bg-neutral-100 px-4 py-3 dark:bg-neutral-800">
                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Total Applicants</div>
                        <div class="mt-1 text-lg font-semibold">{{ $application->examination->applications()->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3">
            <button wire:click="goBack"
                class="inline-flex items-center gap-2 rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Applications
            </button>
        </div>

    </div>
    @else
    <div class="mt-8 rounded-xl border border-neutral-200 bg-white p-12 text-center dark:border-neutral-700 dark:bg-zinc-900">
        <svg class="mx-auto size-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <h3 class="mt-4 text-sm font-medium text-zinc-900 dark:text-white">Access Restricted</h3>
        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            You don't have permission to view this application.
        </p>
    </div>
    @endrole
</div>
