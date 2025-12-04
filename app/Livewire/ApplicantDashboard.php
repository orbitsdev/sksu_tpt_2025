<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\Examination;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplicantDashboard extends Component
{
    /**
     * View application details
     */
    public function viewApplication($applicationId)
    {
        // Navigate to applications page where they can view details
        return redirect()->route('applicant.applications');
    }

    /**
     * Start new application for an examination
     */
    public function startApplication($examinationId)
    {
        // TODO: Implement application creation flow
        // For now, show notification
        session()->flash('message', 'Application feature coming soon for Examination #' . $examinationId);
    }

    public function render()
    {
        $user = Auth::user();

        // Get applicant's current applications
        $myApplications = Application::where('user_id', $user->id)
            ->with(['examination.examinationSlots.rooms'])
            ->latest()
            ->get();

        // Get active examinations (published and accepting applications)
        $activeExaminations = Examination::where('is_published', true)
            ->where('is_application_open', true)
            ->with(['examinationSlots.rooms'])
            ->withCount(['examinationSlots', 'applications'])
            ->latest()
            ->get()
            ->map(function ($exam) {
                // Calculate total capacity and available slots
                $totalCapacity = $exam->examinationSlots->flatMap(fn($slot) => $slot->rooms)->sum('capacity');
                $totalOccupied = $exam->examinationSlots->flatMap(fn($slot) => $slot->rooms)->sum('occupied');
                $exam->available_slots = max($totalCapacity - $totalOccupied, 0);
                $exam->total_capacity = $totalCapacity;
                return $exam;
            });

        // Get upcoming examinations (published but applications not open yet)
        $upcomingExaminations = Examination::where('is_published', true)
            ->where('is_application_open', false)
            ->with(['examinationSlots'])
            ->latest()
            ->get();

        return view('livewire.applicant-dashboard', [
            'myApplications' => $myApplications,
            'activeExaminations' => $activeExaminations,
            'upcomingExaminations' => $upcomingExaminations,
        ])->layout('components.layouts.applicant');
    }
}
