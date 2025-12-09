<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-full">
        {{-- Header --}}
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <h1 class="text-xl font-semibold text-gray-900">SKSU Examination Portal</h1>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="py-10">
            <main>
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                    {{-- Page Header --}}
                    <div class="px-4 sm:px-0">
                        <h2 class="text-2xl font-semibold text-gray-900">Dashboard</h2>
                        <p class="mt-1 text-sm text-gray-600">Welcome back! Here's an overview of your examinations.</p>
                    </div>

                    <div class="mt-8 space-y-6">

                        {{-- My Applications Section --}}
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b border-gray-200 bg-white px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Your Applications</h3>
                            </div>
                            <div class="px-6 py-6">
                                @forelse($myApplications as $application)
                                    <div class="mb-4 last:mb-0 rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-base font-semibold text-gray-900">
                                                    {{ $application->examination->title }}
                                                </h4>
                                                <div class="mt-2 flex items-center gap-4 text-sm">
                                                    <span class="text-gray-600">
                                                        Application #: <span class="font-medium text-gray-900">{{ $application->exam_number }}</span>
                                                    </span>
                                                    <span class="inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    Applied on {{ $application->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                            <div class="ml-4">
                                                <a href="#" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No applications yet</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by applying to an available examination below.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Available Examinations Section --}}
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b border-gray-200 bg-white px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Available Examinations</h3>
                                <p class="mt-1 text-sm text-gray-500">Applications are currently open for these examinations</p>
                            </div>
                            <div class="px-6 py-6">
                                @forelse($activeExaminations as $exam)
                                    <div class="mb-4 last:mb-0 rounded-lg border border-gray-200 p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <h4 class="text-lg font-semibold text-gray-900">
                                                        {{ $exam->title }}
                                                    </h4>
                                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        <svg class="mr-1 h-2 w-2 fill-green-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                                        OPEN
                                                    </span>
                                                </div>

                                                <div class="mt-3 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                                                    <div>
                                                        <span class="text-gray-500">School Year:</span>
                                                        <span class="ml-1 font-medium text-gray-900">{{ $exam->school_year }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Type:</span>
                                                        <span class="ml-1 font-medium text-gray-900">{{ ucfirst($exam->exam_type) }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Exam Date:</span>
                                                        <span class="ml-1 font-medium text-gray-900">{{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Slots:</span>
                                                        <span class="ml-1 font-medium text-gray-900">{{ $exam->examination_slots_count }}</span>
                                                    </div>
                                                </div>

                                                <div class="mt-3 flex gap-3">
                                                    <div class="rounded-md bg-gray-50 px-3 py-2">
                                                        <div class="text-xs text-gray-500">Total Capacity</div>
                                                        <div class="mt-0.5 text-base font-semibold text-gray-900">{{ $exam->total_capacity }}</div>
                                                    </div>
                                                    <div class="rounded-md bg-gray-50 px-3 py-2">
                                                        <div class="text-xs text-gray-500">Available</div>
                                                        <div class="mt-0.5 text-base font-semibold text-gray-900">{{ $exam->available_slots }}</div>
                                                    </div>
                                                    <div class="rounded-md bg-gray-50 px-3 py-2">
                                                        <div class="text-xs text-gray-500">Applicants</div>
                                                        <div class="mt-0.5 text-base font-semibold text-gray-900">{{ $exam->applications_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-6">
                                                <a href="#" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                                                    Apply Now
                                                    <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No examinations available</h3>
                                        <p class="mt-1 text-sm text-gray-500">There are currently no examinations accepting applications.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Upcoming Examinations Section --}}
                        @if($upcomingExaminations->count() > 0)
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b border-gray-200 bg-white px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Upcoming Examinations</h3>
                                <p class="mt-1 text-sm text-gray-500">Applications will open soon</p>
                            </div>
                            <div class="px-6 py-6">
                                @foreach($upcomingExaminations as $exam)
                                    <div class="mb-4 last:mb-0 rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <h4 class="text-base font-semibold text-gray-900">
                                                        {{ $exam->title }}
                                                    </h4>
                                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                        SOON
                                                    </span>
                                                </div>
                                                <div class="mt-2 flex items-center gap-4 text-sm">
                                                    <span class="text-gray-600">
                                                        Exam Date: <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }}</span>
                                                    </span>
                                                    <span class="text-gray-600">
                                                        Slots: <span class="font-medium text-gray-900">{{ $exam->examination_slots_count }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <button disabled class="inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                                                    Applications Closed
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
