<?php

namespace App\Enums;

/**
 * Application Step Enum
 *
 * Centralized definition of all application steps with descriptions.
 * Based on APPLICATION_STEPS.md documentation.
 *
 * To add a new step:
 * 1. Add the case with step number
 * 2. Update getLabel() method
 * 3. Update getDescription() method
 * 4. Update APPLICATION_STEPS.md documentation
 */
enum ApplicationStep: int
{
    // 1. APPLICANT STEPS
    case ACCOUNT_CREATION = 10;
    case PERSONAL_INFORMATION = 20;
    case APPLICATION_INFORMATION = 30;
    case PAYMENT_INFORMATION = 40;
    case SUBMITTED_FOR_VERIFICATION = 59;

    // 2. STAFF / CASHIER STEPS
    case RECEIVED_FOR_REVIEW = 60;
    case REJECTED = 58;

    // 3. APPROVAL & SLOT SELECTION
    case APPROVED = 70;

    // 4. EXAM PHASE
    case SLOT_ASSIGNED = 80;
    case EXAM_COMPLETED = 85;

    // 5. RESULT PHASE
    case WAITING_FOR_RESULTS = 90;
    case RESULTS_PUBLISHED = 95;
    case PASSED = 96;
    case FAILED = 97;

    // 6. INTERVIEW PHASE
    case INTERVIEW_COMPLETED = 98;
    case INTERVIEW_RESULT_RELEASED = 99;

    // 7. FINAL DECISION
    case ADMISSION_FINALIZED = 100;

    /**
     * Get the human-readable label for this step
     */
    public function getLabel(): string
    {
        return match($this) {
            self::ACCOUNT_CREATION => 'Account Creation',
            self::PERSONAL_INFORMATION => 'Personal Information',
            self::APPLICATION_INFORMATION => 'Application Information',
            self::PAYMENT_INFORMATION => 'Payment Information',
            self::SUBMITTED_FOR_VERIFICATION => 'Submitted for Verification',
            self::RECEIVED_FOR_REVIEW => 'Received for Review',
            self::REJECTED => 'Rejected',
            self::APPROVED => 'Approved',
            self::SLOT_ASSIGNED => 'Slot Assigned & Waiting for Exam Day',
            self::EXAM_COMPLETED => 'Exam Completed',
            self::WAITING_FOR_RESULTS => 'Waiting for Results',
            self::RESULTS_PUBLISHED => 'Results Published',
            self::PASSED => 'Passed (Proceed to Interview)',
            self::FAILED => 'Failed (Not Qualified)',
            self::INTERVIEW_COMPLETED => 'Interview Completed',
            self::INTERVIEW_RESULT_RELEASED => 'Interview Result Released',
            self::ADMISSION_FINALIZED => 'Admission Decision Finalized',
        };
    }

    /**
     * Get the detailed description for this step
     */
    public function getDescription(): string
    {
        return match($this) {
            self::ACCOUNT_CREATION => 'Applicant creates an account and enters the system for the first time.',
            self::PERSONAL_INFORMATION => 'Applicant fills out personal details (name, contact, address, DOB, etc.).',
            self::APPLICATION_INFORMATION => 'Applicant completes educational background, priority programs, documents, and additional details.',
            self::PAYMENT_INFORMATION => 'Applicant reaches the payment section and submits payment (cashier or online). Status remains here until payment is processed.',
            self::SUBMITTED_FOR_VERIFICATION => 'Applicant submits their entire application for staff review. Used both for first submission and re-submission after rejection.',
            self::RECEIVED_FOR_REVIEW => 'Staff or cashier has received the application and is reviewing details and payment validity.',
            self::REJECTED => 'The staff rejects the application with a reason. Applicant must read the remarks and correct their information, then submit again.',
            self::APPROVED => 'Application is verified and approved. Applicant is now allowed to choose an examination slot.',
            self::SLOT_ASSIGNED => 'Applicant has selected a slot (and room/seat if applicable). System waits until exam day.',
            self::EXAM_COMPLETED => 'Applicant has finished taking the exam.',
            self::WAITING_FOR_RESULTS => 'Exam is finished but result publication is not yet available.',
            self::RESULTS_PUBLISHED => 'Results are now available for the applicant to view.',
            self::PASSED => 'Applicant passes exam cutoffs and is eligible for program evaluation/interview.',
            self::FAILED => 'Applicant does not meet required cutoffs. Application process ends here.',
            self::INTERVIEW_COMPLETED => 'Applicant attended the interview and is waiting for evaluation.',
            self::INTERVIEW_RESULT_RELEASED => 'Interview result (Passed / Failed / Waitlisted) is officially released.',
            self::ADMISSION_FINALIZED => 'Final program assignment or admission status is completed. This is the last step of the application lifecycle.',
        };
    }

    /**
     * Get the category/phase this step belongs to
     */
    public function getCategory(): string
    {
        return match($this) {
            self::ACCOUNT_CREATION,
            self::PERSONAL_INFORMATION,
            self::APPLICATION_INFORMATION,
            self::PAYMENT_INFORMATION,
            self::SUBMITTED_FOR_VERIFICATION => 'Applicant Steps',

            self::RECEIVED_FOR_REVIEW,
            self::REJECTED => 'Staff / Cashier Steps',

            self::APPROVED => 'Approval & Slot Selection',

            self::SLOT_ASSIGNED,
            self::EXAM_COMPLETED => 'Exam Phase',

            self::WAITING_FOR_RESULTS,
            self::RESULTS_PUBLISHED,
            self::PASSED,
            self::FAILED => 'Result Phase',

            self::INTERVIEW_COMPLETED,
            self::INTERVIEW_RESULT_RELEASED => 'Interview Phase',

            self::ADMISSION_FINALIZED => 'Final Decision',
        };
    }

    /**
     * Check if this step is a terminal state (no further progression)
     */
    public function isTerminal(): bool
    {
        return in_array($this, [
            self::FAILED,
            self::ADMISSION_FINALIZED,
        ]);
    }

    /**
     * Check if this step requires action from applicant
     */
    public function requiresApplicantAction(): bool
    {
        return in_array($this, [
            self::ACCOUNT_CREATION,
            self::PERSONAL_INFORMATION,
            self::APPLICATION_INFORMATION,
            self::PAYMENT_INFORMATION,
            self::SUBMITTED_FOR_VERIFICATION,
            self::REJECTED,
            self::APPROVED, // Choose exam slot
        ]);
    }

    /**
     * Check if this step requires action from staff/cashier
     */
    public function requiresStaffAction(): bool
    {
        return in_array($this, [
            self::RECEIVED_FOR_REVIEW,
        ]);
    }

    /**
     * Get color class for UI display
     */
    public function getColorClass(): string
    {
        return match($this) {
            self::REJECTED, self::FAILED => 'red',
            self::APPROVED, self::PASSED, self::ADMISSION_FINALIZED => 'green',
            self::SUBMITTED_FOR_VERIFICATION, self::RECEIVED_FOR_REVIEW => 'amber',
            self::SLOT_ASSIGNED, self::WAITING_FOR_RESULTS => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get icon for UI display
     */
    public function getIcon(): string
    {
        return match($this) {
            self::ACCOUNT_CREATION => 'heroicon-o-user-plus',
            self::PERSONAL_INFORMATION => 'heroicon-o-identification',
            self::APPLICATION_INFORMATION => 'heroicon-o-document-text',
            self::PAYMENT_INFORMATION => 'heroicon-o-credit-card',
            self::SUBMITTED_FOR_VERIFICATION => 'heroicon-o-paper-airplane',
            self::RECEIVED_FOR_REVIEW => 'heroicon-o-eye',
            self::REJECTED => 'heroicon-o-x-circle',
            self::APPROVED => 'heroicon-o-check-circle',
            self::SLOT_ASSIGNED => 'heroicon-o-calendar',
            self::EXAM_COMPLETED => 'heroicon-o-clipboard-document-check',
            self::WAITING_FOR_RESULTS => 'heroicon-o-clock',
            self::RESULTS_PUBLISHED => 'heroicon-o-document-check',
            self::PASSED => 'heroicon-o-trophy',
            self::FAILED => 'heroicon-o-x-mark',
            self::INTERVIEW_COMPLETED => 'heroicon-o-chat-bubble-left-right',
            self::INTERVIEW_RESULT_RELEASED => 'heroicon-o-megaphone',
            self::ADMISSION_FINALIZED => 'heroicon-o-academic-cap',
        };
    }

    /**
     * Get step from integer value
     */
    public static function fromValue(int $value): ?self
    {
        return self::tryFrom($value);
    }

    /**
     * Get all steps as array with details
     */
    public static function getAllSteps(): array
    {
        return array_map(
            fn(self $step) => [
                'value' => $step->value,
                'label' => $step->getLabel(),
                'description' => $step->getDescription(),
                'category' => $step->getCategory(),
            ],
            self::cases()
        );
    }
}
