<?php

namespace App\Livewire;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicantApplications extends Component
{
    use WithPagination;

    public $statusFilter = 'all';

    /**
     * View application details
     */
    public function viewApplication($applicationId)
    {
        return redirect()->route('applicant.application.view', ['id' => $applicationId]);
    }

    /**
     * Filter applications by status
     */
    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        $query = Application::where('user_id', $user->id)
            ->with(['examination.examinationSlots.rooms']);

        // Apply status filter based on current_step
        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'pending') {
                // Pending: current_step < 70 and not rejected (step 58)
                $query->where('current_step', '<', 70)
                      ->where('current_step', '!=', 58);
            } elseif ($this->statusFilter === 'approved') {
                // Approved: current_step >= 70
                $query->where('current_step', '>=', 70);
            } elseif ($this->statusFilter === 'rejected') {
                // Rejected: current_step = 58
                $query->where('current_step', 58);
            }
        }

        $applications = $query->latest()->paginate(10);

        // Get status counts for filter buttons based on current_step
        $statusCounts = [
            'all' => Application::where('user_id', $user->id)->count(),
            'pending' => Application::where('user_id', $user->id)
                ->where('current_step', '<', 70)
                ->where('current_step', '!=', 58)
                ->count(),
            'approved' => Application::where('user_id', $user->id)
                ->where('current_step', '>=', 70)
                ->count(),
            'rejected' => Application::where('user_id', $user->id)
                ->where('current_step', 58)
                ->count(),
        ];

        return view('livewire.applicant-applications', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
        ])->layout('components.layouts.applicant');
    }
}
