@php
    // Payment is passed from Application->payment relationship
    $application = $payment?->application;
    $applicant = $application?->user; // Use application's user relationship
    $info = $application?->applicationInformation;
    $slot = $application?->applicationSlot;
    $photo = $application?->getFirstMediaUrl('photo');
    $examination = $application?->examination;
@endphp

<div class="space-y-2">
    <!-- Status Badge + Exam Info Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <!-- Status Badge -->
        @if($payment->status === 'VERIFIED')
            <div class="rounded-lg border border-green-200 bg-green-50 p-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <div class="text-xs font-semibold text-green-900">Payment Verified</div>
                            <div class="text-[11px] text-green-700">
                                {{ $payment->verified_at?->format('M d, Y · g:i A') }}
                                @if($payment->verifiedBy) by {{ $payment->verifiedBy->name }}@endif
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
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-xs font-semibold text-amber-900">Pending Verification</div>
                        <div class="text-[11px] text-amber-700">Awaiting approval</div>
                    </div>
                </div>
            </div>
        @elseif($payment->status === 'REJECTED')
            <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-xs font-semibold text-red-900">Payment Rejected</div>
                        <div class="text-[11px] text-red-700">Please resubmit with valid proof</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Examination Info -->
        @if($examination)
            <div class="rounded-lg border border-slate-100 p-3">
                <div class="text-[11px] uppercase text-slate-500 font-medium mb-1">Examination</div>
                <div class="text-xs font-semibold text-slate-900">{{ $examination->title }}</div>
                <div class="text-[11px] text-slate-600">SY: {{ $examination->school_year }}</div>
                @if($application?->examinee_number && $payment->status !== 'VERIFIED')
                    <div class="text-[11px] text-slate-500 pt-1.5 border-t border-slate-100 mt-1.5">
                        Examinee: <span class="font-mono font-semibold text-slate-900">{{ $application->examinee_number }}</span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Main 2-Column Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <!-- LEFT COLUMN -->
        <div class="space-y-2">
            <!-- Applicant Info -->
            <div class="rounded-lg border border-slate-100 p-3">
                <div class="text-[11px] uppercase text-slate-500 font-medium mb-2">Applicant Information</div>
                <div class="flex gap-3">
                    @if($photo)
                        <img src="{{ $photo }}" alt="Photo" class="w-20 h-20 rounded-lg object-cover border border-slate-200">
                    @else
                        <div class="w-20 h-20 rounded-lg bg-slate-200 flex items-center justify-center border border-slate-300">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-slate-900">{{ $info?->full_name ?? $applicant?->name ?? 'Unknown' }}</div>
                        <div class="text-[11px] text-slate-600 font-medium">{{ $application?->firstPriorityProgram?->name ?? 'No Program' }}</div>
                        <div class="text-[11px] text-slate-500">{{ $info?->type ?? 'N/A' }} · Code: {{ $application?->firstPriorityProgram?->code ?? 'N/A' }}</div>
                        <div class="text-[11px] text-slate-500 mt-1">
                            DOB: {{ $info?->date_of_birth?->format('M d, Y') ?? 'N/A' }} · Sex: {{ $info?->sex ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 pt-2 mt-2 border-t border-slate-200">
                    <div>
                        <div class="text-[10px] text-slate-500">Contact</div>
                        <div class="text-[11px] font-medium text-slate-800">{{ $info?->contact_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500">Email</div>
                        <div class="text-[11px] font-medium text-slate-800 truncate">{{ $applicant?->email ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Address + Educational Background Combined -->
            <div class="rounded-lg border border-slate-100 p-3 space-y-2">
                @if($info?->present_address || $info?->permanent_address)
                    <div>
                        <div class="text-[11px] uppercase text-slate-500 font-medium mb-1.5">Address</div>
                        @if($info?->present_address)
                            <div class="mb-1.5">
                                <div class="text-[10px] text-slate-500">Present</div>
                                <div class="text-[11px] font-medium text-slate-800">{{ $info->present_address }}</div>
                            </div>
                        @endif
                        @if($info?->permanent_address)
                            <div>
                                <div class="text-[10px] text-slate-500">Permanent</div>
                                <div class="text-[11px] font-medium text-slate-800">{{ $info->permanent_address }}</div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="{{ ($info?->present_address || $info?->permanent_address) ? 'pt-2 border-t border-slate-200' : '' }}">
                    <div class="text-[11px] uppercase text-slate-500 font-medium mb-1.5">Education</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <div class="text-[10px] text-slate-500">School</div>
                            <div class="text-[11px] font-medium text-slate-800">{{ $info?->school_graduated ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-500">Year</div>
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
            </div>

            <!-- Program Choices -->
            <div class="rounded-lg border border-slate-100 p-3">
                <div class="text-[11px] uppercase text-slate-500 font-medium mb-1.5">Program Choices</div>
                <div class="space-y-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] text-slate-500">1st Priority:</span>
                        <span class="text-[11px] font-medium text-slate-800 text-right">{{ $application?->firstPriorityProgram?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] text-slate-500">2nd Priority:</span>
                        <span class="text-[11px] font-medium text-slate-800 text-right">{{ $application?->secondPriorityProgram?->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-2">
            <!-- Test Center + Exam Schedule Combined -->
            @if($slot && $slot->examinationSlot)
                <div class="rounded-lg border border-slate-100 p-3">
                    <div class="text-[11px] uppercase text-slate-500 font-medium mb-2">Test Center & Schedule</div>
                    @if($slot->examinationSlot->testCenter)
                        <div class="mb-2">
                            <div class="text-xs font-semibold text-slate-900">{{ $slot->examinationSlot->testCenter->name }}</div>
                            @if($slot->examinationSlot->testCenter->address)
                                <div class="text-[11px] text-slate-600 mt-1">
                                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $slot->examinationSlot->testCenter->address }}
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="pt-2 border-t border-slate-200">
                        <div class="text-xs font-semibold text-slate-900">{{ $slot->examinationSlot->date_of_exam?->format('F d, Y') ?? 'TBA' }}</div>
                        @if($slot->examinationRoom)
                            <div class="text-[11px] text-slate-600 mt-1">Room: <span class="font-medium text-slate-900">{{ $slot->examinationRoom->room_number ?? 'TBA' }}</span></div>
                        @endif
                        @if($slot->seat_number)
                            <div class="text-[11px] text-slate-600">Seat: <span class="font-semibold text-slate-900">{{ $slot->seat_number }}</span></div>
                        @endif
                        @if($slot->examinationSlot->total_examinees)
                            <div class="text-[10px] text-slate-500 pt-1.5 mt-1.5 border-t border-slate-100">
                                Capacity: <span class="font-medium text-slate-700">{{ $slot->examinationSlot->assigned_students_count ?? 0 }} / {{ $slot->examinationSlot->total_examinees }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="rounded-lg border border-slate-100 p-3">
                    <div class="text-[11px] uppercase text-slate-500 font-medium mb-2">Test Center & Schedule</div>
                    <div class="flex items-start gap-2 text-slate-500">
                        <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <div class="text-[11px] text-slate-600 font-medium">Not scheduled yet</div>
                            <div class="text-[10px] text-slate-400 mt-1">
                                @if($payment->status === 'VERIFIED')
                                    Use "Assign Exam Slot" to schedule.
                                @else
                                    Will be assigned after verification.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Details -->
            <div class="rounded-lg border border-slate-100 p-3">
                <div class="flex items-start justify-between mb-2">
                    <div class="text-[11px] uppercase text-slate-500 font-medium">Payment Details</div>
                    <div class="text-right text-[11px] {{ $payment->status === 'REJECTED' ? 'text-red-600' : ($payment->status === 'VERIFIED' ? 'text-green-600' : 'text-amber-600') }}">
                        <div class="font-semibold">{{ $payment->status }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-sm font-semibold text-slate-900">₱{{ number_format($payment->amount, 2) }}</div>
                    <div class="text-[11px] text-slate-500">Type: <span class="font-medium text-slate-800">{{ str_replace('_', ' ', $payment->payment_method) }}</span></div>
                    <div class="text-[11px] text-slate-500">
                        Reference: <span class="font-mono text-[11px] {{ $payment->payment_reference ? 'bg-slate-50 px-1.5 py-0.5 rounded' : 'text-slate-400' }}">{{ $payment->payment_reference ?? 'N/A' }}</span>
                    </div>
                    <div class="text-[11px] text-slate-500">
                        OR No: <span class="font-mono text-[11px] {{ $payment->official_receipt_number ? 'bg-green-50 px-1.5 py-0.5 rounded text-green-700 font-semibold' : 'text-slate-400' }}">{{ $payment->official_receipt_number ?? 'Not issued' }}</span>
                    </div>
                    <div class="text-[11px] text-slate-500">Paid: <span class="font-medium text-slate-800">{{ $payment->paid_at?->format('M d, Y · g:i A') ?? 'N/A' }}</span></div>
                </div>

                @if($payment->status === 'REJECTED' && $payment->rejection_reason)
                    <div class="mt-2 p-2 rounded bg-red-50 border border-red-200">
                        <div class="text-[10px] text-red-600 font-medium">Rejection Reason:</div>
                        <div class="text-[11px] text-red-800 mt-0.5">{{ $payment->rejection_reason }}</div>
                    </div>
                @endif
            </div>

            <!-- Receipt Image -->
            @if($payment->getFirstMediaUrl('receipt'))
                <div class="rounded-lg border border-slate-100 p-3">
                    <div class="text-[11px] text-slate-500 mb-2">Proof of Payment</div>
                    <a href="{{ $payment->getFirstMediaUrl('receipt') }}" target="_blank" class="block">
                        <img src="{{ $payment->getFirstMediaUrl('receipt') }}" alt="Receipt" class="w-full rounded-lg border border-slate-200 hover:border-slate-400 transition-colors cursor-pointer">
                    </a>
                    <div class="text-[10px] text-slate-400 mt-1.5 text-center">Click to view full size</div>
                </div>
            @else
                <div class="rounded-lg border border-slate-100 p-3">
                    <div class="text-[11px] text-slate-500 mb-2">Proof of Payment</div>
                    <div class="h-20 w-full rounded border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center text-[11px] text-slate-400">
                        No receipt uploaded
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
