# APPLICATION_STEPS.md
### SKSU-TPT Simplified Application Step Model (Milestone-Based)
This document defines the official system steps used for tracking an applicant's progress from account creation to final admission decision.
Only major milestones are included — no sub-steps, no complicated ranges, no unnecessary progression detail.

> **⚙️ Implementation Note:**
> All steps are centrally defined in `app/Enums/ApplicationStep.php` enum.
> To add or update steps, modify the enum file and then update this documentation.
> This ensures consistency across the entire codebase.

## 1. APPLICANT STEPS

### Step 10 — Account Creation
Applicant creates an account and enters the system for the first time.

### Step 20 — Personal Information
Applicant fills out personal details (name, contact, address, DOB, etc.).

### Step 30 — Application Information
Applicant completes educational background, priority programs, documents, and additional details.

### Step 40 — Payment Information
Applicant reaches the payment section and submits payment (cashier or online).
Status remains here until payment is processed.

### Step 59 — Submitted for Verification (Pending)
Applicant submits their entire application for staff review.
Used both for first submission and re-submission after rejection.

## 2. STAFF / CASHIER STEPS

### Step 60 — Received for Review
Staff or cashier has received the application and is reviewing details and payment validity.

### Step 58 — Rejected (Applicant Must Revise)
The staff rejects the application with a reason.
Applicant must read the remarks and correct their information, then submit again (back to Step 59).

## 3. APPROVAL & SLOT SELECTION

### Step 70 — Approved (Select Exam Slot)
Application is verified and approved.
Applicant is now allowed to choose an examination slot.

## 4. EXAM PHASE

### Step 80 — Slot Assigned & Waiting for Exam Day
Applicant has selected a slot (and room/seat if applicable).
System waits until exam day.

### Step 85 — Exam Completed
Applicant has finished taking the exam.

## 5. RESULT PHASE

### Step 90 — Waiting for Results
Exam is finished but result publication is not yet available.

### Step 95 — Results Published
Results are now available for the applicant to view.

### Step 96 — Passed (Proceed to Interview)
Applicant passes exam cutoffs and is eligible for program evaluation/interview.

### Step 97 — Failed (Not Qualified)
Applicant does not meet required cutoffs.
Application process ends here.

## 6. INTERVIEW PHASE

### Step 98 — Interview Completed
Applicant attended the interview and is waiting for evaluation.

### Step 99 — Interview Result Released
Interview result (Passed / Failed / Waitlisted) is officially released.

## 7. FINAL DECISION

### Step 100 — Admission Decision Finalized
Final program assignment or admission status is completed.
This is the last step of the application lifecycle.

## Notes
- These steps are intentionally simple, milestone-based, and easy to manage in code.
- Step numbers jump (10, 20, 30…) to allow future sub-steps if needed without breaking existing logic.
- Step 59 is reused for both first-time submission and re-submission after rejection.

---

## Usage Examples

### Using the ApplicationStep Enum in Code

```php
use App\Enums\ApplicationStep;

// Get step instance from integer
$step = ApplicationStep::fromValue(70);

// Get label and description
echo $step->getLabel();        // "Approved"
echo $step->getDescription();  // "Application is verified and approved..."

// Get category/phase
echo $step->getCategory();     // "Approval & Slot Selection"

// Get UI helpers
echo $step->getColorClass();   // "green"
echo $step->getIcon();         // "heroicon-o-check-circle"

// Check step conditions
if ($step->requiresApplicantAction()) {
    // Show action needed message
}

if ($step->isTerminal()) {
    // This is the end of the process
}

// Update application step
$application->update([
    'current_step' => ApplicationStep::APPROVED->value,
    'step_description' => ApplicationStep::APPROVED->getDescription(),
]);

// Get all steps for dropdown or documentation
$allSteps = ApplicationStep::getAllSteps();
```

### Adding a New Step

1. **Update the Enum** (`app/Enums/ApplicationStep.php`):
   ```php
   case NEW_STEP_NAME = 75;  // Add between existing steps
   ```

2. **Update Helper Methods** in the enum:
   - Add case to `getLabel()`
   - Add case to `getDescription()`
   - Add case to `getCategory()`
   - Optionally add to condition methods (`isTerminal()`, etc.)

3. **Update This Documentation**: Add the new step description in the appropriate section

4. **Update Related Code**: Search codebase for hardcoded step numbers and replace with enum

### Benefits
- ✅ Single source of truth for all step definitions
- ✅ Type-safe step handling with PHP enums
- ✅ Easy to refactor - change in one place, updates everywhere
- ✅ Auto-completion support in IDEs
- ✅ Consistent labels and descriptions across UI
- ✅ Built-in helper methods for common operations
