@php
    // Payment is passed from Application->payment relationship
    $application = $payment?->application;
    $applicant = $application?->user; // Use application's user relationship
    $info = $application?->applicationInformation;
    $slot = $application?->applicationSlot;
    $photo = $application?->getFirstMediaUrl('photo');
@endphp

<div class="p-4 space-y-4 text-xs">
    <!-- Applicant block with photo -->
    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3 space-y-2">
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
                <div class="text-sm font-semibold text-slate-900">
                    {{ $info?->full_name ?? $applicant?->name ?? 'Unknown' }}
                </div>
                <div class="text-[11px] text-slate-500">
                    {{ $info?->type ?? 'N/A' }} · {{ $application?->firstPriorityProgram?->code ?? 'N/A' }}
                </div>
                <div class="text-[11px] text-slate-500 mt-1">
                    <span class="font-medium">DOB:</span> {{ $info?->date_of_birth?->format('M d, Y') ?? 'N/A' }} ·
                    <span class="font-medium">Sex:</span> {{ $info?->sex ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200">
            <div>
                <div class="text-[10px] text-slate-500">Contact Number</div>
                <div class="font-medium text-slate-800">{{ $info?->contact_number ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-[10px] text-slate-500">Email</div>
                <div class="font-medium text-slate-800">{{ $applicant?->email ?? 'N/A' }}</div>
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
                <div class="font-medium text-slate-800">{{ $info?->school_graduated ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-[10px] text-slate-500">Year Graduated</div>
                <div class="font-medium text-slate-800">{{ $info?->year_graduated ?? 'N/A' }}</div>
            </div>
            @if($info?->track_and_strand_taken)
                <div class="col-span-2">
                    <div class="text-[10px] text-slate-500">Track & Strand</div>
                    <div class="font-medium text-slate-800">{{ $info->track_and_strand_taken }}</div>
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
                <span class="font-medium text-slate-800">{{ $application?->firstPriorityProgram?->name ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] text-slate-500">2nd Priority:</span>
                <span class="font-medium text-slate-800">{{ $application?->secondPriorityProgram?->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Exam & seat -->
    @if($slot)
        <div class="rounded-xl border border-slate-100 p-3 space-y-1">
            <div class="text-[11px] uppercase text-slate-500 font-medium">
                Examination Slot
            </div>
            <div class="text-sm font-semibold text-slate-900">
                {{ $slot->examinationSlot?->schedule_date?->format('F d, Y') ?? 'TBA' }} ·
                {{ $slot->examinationSlot?->start_time?->format('g:i A') ?? '' }} –
                {{ $slot->examinationSlot?->end_time?->format('g:i A') ?? '' }}
            </div>
            <div class="text-[11px] text-slate-500">
                {{ $slot->examinationSlot?->examinationRoom?->testCenter?->name ?? 'TBA' }} ·
                {{ $slot->examinationSlot?->examinationRoom?->building ?? '' }} ·
                {{ $slot->examinationSlot?->examinationRoom?->name ?? 'TBA' }}
            </div>
            <div class="flex items-center justify-between mt-2 text-[11px]">
                <div class="text-slate-500">
                    Seat Number:
                    <span class="font-semibold text-slate-900">{{ $slot->seat_number ?? 'TBA' }}</span>
                </div>
                <div class="text-slate-500">
                    Capacity:
                    <span class="font-semibold text-slate-900">
                        {{ $slot->examinationSlot?->current_capacity ?? 0 }} / {{ $slot->examinationSlot?->max_capacity ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-xl border border-slate-100 p-3">
            <div class="text-[11px] uppercase text-slate-500 font-medium mb-1">
                Examination Slot
            </div>
            <div class="text-sm text-slate-400">Not scheduled yet</div>
        </div>
    @endif

    <!-- Payment block -->
    <div class="rounded-xl border border-slate-100 p-3 space-y-2">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-[11px] uppercase text-slate-500 font-medium">
                    Payment Details
                </div>
                <div class="text-sm font-semibold text-slate-900">
                    ₱{{ number_format($payment->amount, 2) }}
                </div>
                <div class="text-[11px] text-slate-500">
                    Payment Type:
                    <span class="font-medium text-slate-800">
                        {{ str_replace('_', ' ', $payment->payment_method) }}
                    </span>
                </div>
                @if($payment->payment_reference)
                    <div class="text-[11px] text-slate-500">
                        Reference No:
                        <span class="font-mono text-[11px] bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">
                            {{ $payment->payment_reference }}
                        </span>
                    </div>
                @endif
                <div class="text-[11px] text-slate-500">
                    Date Paid:
                    <span class="font-medium text-slate-800">
                        {{ $payment->paid_at?->format('M d, Y · g:i A') ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="text-right text-[11px] text-amber-600">
                Status:
                <div class="font-semibold">{{ $payment->status }}</div>
            </div>
        </div>

        <!-- Receipt image -->
        @if($payment->getFirstMediaUrl('receipt'))
            <div class="mt-2">
                <div class="text-[11px] text-slate-500 mb-1">
                    Proof of Payment
                </div>
                <img src="{{ $payment->getFirstMediaUrl('receipt') }}" alt="Receipt" class="w-full rounded-xl border border-slate-200">
            </div>
        @else
            <div class="mt-2">
                <div class="text-[11px] text-slate-500 mb-1">
                    Proof of Payment
                </div>
                <div class="aspect-video w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center text-[11px] text-slate-400">
                    No receipt uploaded
                </div>
            </div>
        @endif
    </div>
</div>
