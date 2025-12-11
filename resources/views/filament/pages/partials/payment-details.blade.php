@php
    // Payment is passed from Application->payment relationship
    $application = $payment?->application;
    $applicant = $application?->user; // Use application's user relationship
    $info = $application?->applicationInformation;
    $slot = $application?->applicationSlot;
    $photo = $application?->getFirstMediaUrl('photo');
    $examination = $application?->examination;
@endphp

<div class=" space-y-2">
    <!-- Status Badge at Top -->
    @if($payment->status === 'VERIFIED')
        <div class="rounded-xl border border-green-200 bg-green-50 p-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-xs font-semibold text-green-900">Payment Verified</div>
                        <div class="text-[10px] text-green-700">
                            Verified {{ $payment->verified_at?->format('M d, Y · g:i A') }}
                            @if($payment->verifiedBy)
                                by {{ $payment->verifiedBy->name }}
                            @endif
                        </div>
                    </div>
                </div>
                @if($application?->examinee_number)
                    <div class="text-right">
                        <div class="text-[10px] text-green-700">Examinee No.</div>
                        <div class="text-sm font-bold text-green-900">{{ $application->examinee_number }}</div>
                    </div>
                @endif
            </div>
        </div>
    @elseif($payment->status === 'PENDING')
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="text-xs font-semibold text-amber-900">Pending Verification</div>
                    <div class="text-[10px] text-amber-700">Awaiting approval</div>
                </div>
            </div>
        </div>
    @elseif($payment->status === 'REJECTED')
        <div class="rounded-xl border border-red-200 bg-red-50 p-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="text-xs font-semibold text-red-900">Payment Rejected</div>
                    <div class="text-[10px] text-red-700">Please resubmit with valid proof</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Examination Info -->
    @if($examination)
        <div class="rounded-xl border border-slate-100 p-3 space-y-1">
            <div class="text-[11px] uppercase text-slate-500 font-medium">
                Examination
            </div>
            <div class="text-xs font-semibold text-slate-900">
                {{ $examination->title }}
            </div>
            <div class="text-[11px] text-slate-600">
                School Year: {{ $examination->school_year }}
            </div>
            @if($application?->examinee_number && $payment->status !== 'VERIFIED')
                <div class="text-[10px] text-slate-500 pt-1 border-t border-slate-100">
                    Examinee No: <span class="font-mono font-semibold text-slate-900">{{ $application->examinee_number }}</span>
                </div>
            @endif
        </div>
    @endif

    <!-- Applicant block with photo -->
    <div class="rounded-xl border border-slate-100 p-3 space-y-2">
        <div class="text-[11px] uppercase text-slate-500 font-medium">
            Applicant Information
        </div>

        <div class="flex gap-3">
            <!-- Photo -->
            @if($photo)
                <img src="{{ $photo }}" alt="Applicant Photo" class="w-20 h-20 rounded-lg object-cover border border-slate-200">
            @else
                <div class="w-20 h-20 rounded-lg bg-slate-200 flex items-center justify-center border border-slate-300">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            @endif

            <!-- Basic Info -->
            <div class="flex-1">
                <div class="text-xs font-semibold text-slate-900">
                    {{ $info?->full_name ?? $applicant?->name ?? 'Unknown' }}
                </div>
                <div class="text-[11px] text-slate-600 font-medium">
                    {{ $application?->firstPriorityProgram?->name ?? 'No Program Selected' }}
                </div>
                <div class="text-[10px] text-slate-500">
                    {{ $info?->type ?? 'N/A' }} · Code: {{ $application?->firstPriorityProgram?->code ?? 'N/A' }}
                </div>
                <div class="text-[10px] text-slate-500 mt-1">
                    <span class="font-medium">DOB:</span> {{ $info?->date_of_birth?->format('M d, Y') ?? 'N/A' }} ·
                    <span class="font-medium">Sex:</span> {{ $info?->sex ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200">
            <div>
                <div class="text-[10px] text-slate-500">Contact Number</div>
                <div class="text-[11px] font-medium text-slate-800">{{ $info?->contact_number ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-[10px] text-slate-500">Email</div>
                <div class="text-[11px] font-medium text-slate-800">{{ $applicant?->email ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Address Information -->
        @if($info?->present_address || $info?->permanent_address)
            <div class="pt-2 border-t border-slate-200 space-y-2">
                @if($info?->present_address)
                    <div>
                        <div class="text-[10px] text-slate-500">Present Address</div>
                        <div class="font-medium text-slate-800 text-[11px]">{{ $info->present_address }}</div>
                    </div>
                @endif
                @if($info?->permanent_address)
                    <div>
                        <div class="text-[10px] text-slate-500">Permanent Address</div>
                        <div class="font-medium text-slate-800 text-[11px]">{{ $info->permanent_address }}</div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Educational Background -->
    <div class="rounded-xl border border-slate-100 p-3 space-y-2">
        <div class="text-[11px] uppercase text-slate-500 font-medium">
            Educational Background
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <div class="text-[10px] text-slate-500">School Graduated</div>
                <div class="text-[11px] font-medium text-slate-800">{{ $info?->school_graduated ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-[10px] text-slate-500">Year Graduated</div>
                <div class="text-[11px] font-medium text-slate-800">{{ $info?->year_graduated ?? 'N/A' }}</div>
            </div>
            @if($info?->track_and_strand_taken)
                <div class="col-span-2">
                    <div class="text-[10px] text-slate-500">Track & Strand</div>
                    <div class="text-[11px] font-medium text-slate-800">{{ $info->track_and_strand_taken }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Program Choices -->
    <div class="rounded-xl border border-slate-100 p-3 space-y-2">
        <div class="text-[11px] uppercase text-slate-500 font-medium">
            Program Choices
        </div>
        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[10px] text-slate-500">1st Priority:</span>
                <span class="text-[11px] font-medium text-slate-800">{{ $application?->firstPriorityProgram?->name ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] text-slate-500">2nd Priority:</span>
                <span class="text-[11px] font-medium text-slate-800">{{ $application?->secondPriorityProgram?->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Test Center -->
    @if($slot && $slot->examinationSlot?->testCenter)
        <div class="rounded-xl border border-slate-100 p-3 space-y-2">
            <div class="text-[11px] uppercase text-slate-500 font-medium">
                Test Center
            </div>
            <div class="space-y-1">
                <div class="text-xs font-semibold text-slate-900">
                    {{ $slot->examinationSlot->testCenter->name }}
                </div>
                @if($slot->examinationSlot->testCenter->address)
                    <div class="text-[11px] text-slate-600">
                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $slot->examinationSlot->testCenter->address }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Exam Slot & Room -->
    @if($slot)
        <div class="rounded-xl border border-slate-100 p-3 space-y-2">
            <div class="text-[11px] uppercase text-slate-500 font-medium">
                Examination Schedule
            </div>
            <div class="text-xs font-semibold text-slate-900">
                {{ $slot->examinationSlot?->date_of_exam?->format('F d, Y') ?? 'TBA' }}
            </div>
            @if($slot->examinationRoom)
                <div class="text-[11px] text-slate-600">
                    Room: <span class="font-medium text-slate-900">{{ $slot->examinationRoom->room_number ?? 'TBA' }}</span>
                </div>
            @endif
            @if($slot->seat_number)
                <div class="text-[11px] text-slate-600">
                    Seat Number: <span class="font-semibold text-slate-900">{{ $slot->seat_number }}</span>
                </div>
            @endif
            @if($slot->examinationSlot?->total_examinees)
                <div class="text-[10px] text-slate-500 pt-1 border-t border-slate-100">
                    Slot Capacity:
                    <span class="font-medium text-slate-700">
                        {{ $slot->examinationSlot->assigned_students_count ?? 0 }} / {{ $slot->examinationSlot->total_examinees }} examinees
                    </span>
                </div>
            @endif
        </div>
    @else
        <div class="rounded-xl border border-slate-100 p-3">
            <div class="text-[11px] uppercase text-slate-500 font-medium mb-2">
                Test Center & Examination Schedule
            </div>
            <div class="flex items-start gap-2 text-slate-500">
                <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="text-[11px] text-slate-600 font-medium">Not scheduled yet</div>
                    <div class="text-[10px] text-slate-400 mt-0.5">
                        @if($payment->status === 'VERIFIED')
                            Use "Assign Exam Slot" button to schedule this applicant.
                        @else
                            Exam slot will be assigned after payment verification.
                        @endif
                    </div>
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
                <div class="text-xs font-semibold text-slate-900">
                    ₱{{ number_format($payment->amount, 2) }}
                </div>
                <div class="text-[11px] text-slate-500">
                    Payment Type:
                    <span class="font-medium text-slate-800">
                        {{ str_replace('_', ' ', $payment->payment_method) }}
                    </span>
                </div>
                <div class="text-[11px] text-slate-500">
                    Payment Reference:
                    <span class="font-mono text-[11px] {{ $payment->payment_reference ? 'bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200' : 'text-slate-400' }}">
                        {{ $payment->payment_reference ?? 'Not provided' }}
                    </span>
                </div>
                <div class="text-[11px] text-slate-500">
                    Official Receipt No:
                    <span class="font-mono text-[11px] {{ $payment->official_receipt_number ? 'bg-green-50 px-1.5 py-0.5 rounded border border-green-200 text-green-700 font-semibold' : 'text-slate-400' }}">
                        {{ $payment->official_receipt_number ?? 'Not issued yet' }}
                    </span>
                </div>
                <div class="text-[11px] text-slate-500">
                    Date Paid:
                    <span class="font-medium text-slate-800">
                        {{ $payment->paid_at?->format('M d, Y · g:i A') ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="text-right text-[11px] {{ $payment->status === 'REJECTED' ? 'text-red-600' : 'text-amber-600' }}">
                Status:
                <div class="font-semibold">{{ $payment->status }}</div>
            </div>
        </div>

        <!-- Rejection Reason (if rejected) -->
        @if($payment->status === 'REJECTED' && $payment->rejection_reason)
            <div class="mt-2 p-2 rounded-lg bg-red-50 border border-red-200">
                <div class="text-[10px] text-red-600 font-medium mb-1">
                    Rejection Reason:
                </div>
                <div class="text-[11px] text-red-800">
                    {{ $payment->rejection_reason }}
                </div>
            </div>
        @endif

        <!-- Receipt image -->
        @if($payment->getFirstMediaUrl('receipt'))
            <div class="mt-2">
                <div class="text-[11px] text-slate-500 mb-1">
                    Proof of Payment
                </div>
                <a href="{{ $payment->getFirstMediaUrl('receipt') }}" target="_blank" class="block">
                    <img src="{{ $payment->getFirstMediaUrl('receipt') }}" alt="Receipt" class="w-full rounded-xl border border-slate-200 hover:border-slate-400 transition-colors cursor-pointer">
                </a>
                <div class="text-[10px] text-slate-400 mt-1 text-center">
                    Click to view full size
                </div>
            </div>
        @else
            <div class="mt-2">
                <div class="text-[11px] text-slate-500 mb-1">
                    Proof of Payment
                </div>
                <div class="h-20 w-full rounded-lg border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center text-[11px] text-slate-400">
                    No receipt uploaded
                </div>
            </div>
        @endif
    </div>
</div>
