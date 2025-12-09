<div>
    {{-- Page Header --}}
    <flux:heading size="xl">My Applications</flux:heading>
    <flux:subheading>View and manage all your examination applications</flux:subheading>

    @role('student')
    <div class="mt-8">
        {{-- Status Filter Tabs --}}
        <div class="border-b border-neutral-200 dark:border-neutral-700">
            <nav class="-mb-px flex gap-6">
                <button wire:click="filterByStatus('all')"
                    class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors {{ $statusFilter === 'all' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white' : 'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                    All Applications
                    <span class="ml-2 rounded-md bg-neutral-100 px-2 py-0.5 text-xs dark:bg-neutral-800">{{ $statusCounts['all'] }}</span>
                </button>
                <button wire:click="filterByStatus('pending')"
                    class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors {{ $statusFilter === 'pending' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white' : 'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                    Pending
                    <span class="ml-2 rounded-md bg-neutral-100 px-2 py-0.5 text-xs dark:bg-neutral-800">{{ $statusCounts['pending'] }}</span>
                </button>
                <button wire:click="filterByStatus('approved')"
                    class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors {{ $statusFilter === 'approved' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white' : 'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                    Approved
                    <span class="ml-2 rounded-md bg-neutral-100 px-2 py-0.5 text-xs dark:bg-neutral-800">{{ $statusCounts['approved'] }}</span>
                </button>
                <button wire:click="filterByStatus('rejected')"
                    class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors {{ $statusFilter === 'rejected' ? 'border-zinc-900 text-zinc-900 dark:border-white dark:text-white' : 'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                    Rejected
                    <span class="ml-2 rounded-md bg-neutral-100 px-2 py-0.5 text-xs dark:bg-neutral-800">{{ $statusCounts['rejected'] }}</span>
                </button>
            </nav>
        </div>

        {{-- Applications List --}}
        <div class="mt-6 space-y-4">
            @forelse($applications as $application)
                <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                        {{ $application->examination->title }}
                                    </h3>
                                    <span class="inline-flex items-center rounded-md border px-2 py-1 text-xs font-medium
                                        @if($application->status === 'pending') border-neutral-300 bg-neutral-100 text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300
                                        @elseif($application->status === 'approved') border-green-300 bg-green-50 text-green-700 dark:border-green-700 dark:bg-green-900/20 dark:text-green-400
                                        @elseif($application->status === 'rejected') border-red-300 bg-red-50 text-red-700 dark:border-red-700 dark:bg-red-900/20 dark:text-red-400
                                        @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">Application #:</span>
                                        <span class="ml-1 font-medium">{{ $application->exam_number }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">School Year:</span>
                                        <span class="ml-1 font-medium">{{ $application->examination->school_year }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">Type:</span>
                                        <span class="ml-1 font-medium">{{ ucfirst($application->examination->exam_type) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500 dark:text-neutral-400">Applied:</span>
                                        <span class="ml-1 font-medium">{{ $application->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                @if($application->examination->start_date)
                                <div class="mt-3 text-sm">
                                    <span class="text-neutral-500 dark:text-neutral-400">Exam Date:</span>
                                    <span class="ml-1 font-medium">{{ \Carbon\Carbon::parse($application->examination->start_date)->format('M d, Y') }}</span>
                                </div>
                                @endif
                            </div>

                            <button wire:click="viewApplication({{ $application->id }})"
                                class="inline-flex items-center gap-2 rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700">
                                View Details
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-zinc-900">
                    <div class="p-12 text-center">
                        <svg class="mx-auto size-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-zinc-900 dark:text-white">No applications found</h3>
                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                            @if($statusFilter === 'all')
                                You haven't applied to any examinations yet.
                            @else
                                You don't have any {{ $statusFilter }} applications.
                            @endif
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('applicant.examinations') }}"
                                class="inline-flex items-center gap-2 rounded-md bg-sksu-green px-4 py-2 text-sm font-medium text-white hover:bg-sksu-green-light">
                                Browse Examinations
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($applications->hasPages())
        <div class="mt-6">
            {{ $applications->links() }}
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
            You don't have permission to view applications.
        </p>
    </div>
    @endrole
</div>
