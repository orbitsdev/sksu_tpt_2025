@php
    // Payment is passed from Application->payment relationship
    $application = $payment?->application;
    $applicant = $application?->user; // Use application's user relationship
    $info = $application?->applicationInformation;
    $slot = $application?->applicationSlot;
@endphp

<div class="p-4 space-y-4 text-xs">
    <!-- Applicant block -->
    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3 space-y-1">
        <div class="text-[11px] uppercase text-slate-500 font-medium">
            Applicant Information
        </div>
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-slate-900">
                    {{ $applicant?->name ?? 'Unknown' }}
                </div>
                <div class="text-[11px] text-slate-500">
                    {{ $info?->applicant_type ?? 'N/A' }} · {{ $application?->firstPriorityProgram?->name ?? 'N/A' }}
                </div>
            </div>
            <div class="text-right text-[10px] text-slate-500">
                Contact:
                <div class="font-medium text-slate-800">
                    {{ $info?->contact_number ?? 'N/A' }}
                </div>
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
        @if($payment->receipt_file)
            <div class="mt-2">
                <div class="text-[11px] text-slate-500 mb-1">
                    Proof of Payment
                </div>
                <img src="{{ Storage::url($payment->receipt_file) }}" alt="Receipt" class="w-full rounded-xl border border-slate-200">
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
