<?php

namespace App\Livewire;

use App\Models\Examination;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicantExaminations extends Component
{
    use WithPagination;

    public $filterType = 'all'; // all, active, upcoming

    /**
     * Start new application for an examination
     */
    public function startApplication($examinationId)
    {
        $user = Auth::user();

        // Check if user already applied to this examination
        $existingApplication = Application::where('user_id', $user->id)
            ->where('examination_id', $examinationId)
            ->first();

        if ($existingApplication) {
            session()->flash('error', 'You have already applied to this examination.');
            return redirect()->route('applicant.applications');
        }

        // TODO: Redirect to application creation wizard
        session()->flash('message', 'Application creation coming soon for Examination #' . $examinationId);
    }

    /**
     * Filter examinations by type
     */
    public function filterByType($type)
    {
        $this->filterType = $type;
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        // Get user's application IDs to check which exams they've already applied to
        $appliedExaminationIds = Application::where('user_id', $user->id)
            ->pluck('examination_id')
            ->toArray();

        $query = Examination::where('is_published', true)
            ->with(['examinationSlots.rooms'])
            ->withCount(['examinationSlots', 'applications']);

        // Apply filter
        if ($this->filterType === 'active') {
            $query->where('is_application_open', true);
        } elseif ($this->filterType === 'upcoming') {
            $query->where('is_application_open', false);
        }

        $examinations = $query->latest()->paginate(10);

        // Transform paginated items while maintaining pagination
        $examinations->through(function ($exam) use ($appliedExaminationIds) {
            // Calculate capacity
            $totalCapacity = $exam->examinationSlots->flatMap(fn($slot) => $slot->rooms)->sum('capacity');
            $totalOccupied = $exam->examinationSlots->flatMap(fn($slot) => $slot->rooms)->sum('occupied');
            $exam->available_slots = max($totalCapacity - $totalOccupied, 0);
            $exam->total_capacity = $totalCapacity;
            $exam->has_applied = in_array($exam->id, $appliedExaminationIds);
            return $exam;
        });

        // Get counts for filter buttons
        $filterCounts = [
            'all' => Examination::where('is_published', true)->count(),
            'active' => Examination::where('is_published', true)->where('is_application_open', true)->count(),
            'upcoming' => Examination::where('is_published', true)->where('is_application_open', false)->count(),
        ];

        return view('livewire.applicant-examinations', [
            'examinations' => $examinations,
            'filterCounts' => $filterCounts,
        ])->layout('components.layouts.applicant');
    }
}
