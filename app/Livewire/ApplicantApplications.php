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

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $applications = $query->latest()->paginate(10);

        // Get status counts for filter buttons
        $statusCounts = [
            'all' => Application::where('user_id', $user->id)->count(),
            'pending' => Application::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Application::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => Application::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('livewire.applicant-applications', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
        ])->layout('components.layouts.applicant');
    }
}
