<?php

namespace App\Livewire;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplicantApplicationView extends Component
{
    public Application $application;

    public function mount($id)
    {
        $user = Auth::user();

        // Load application with relationships
        $this->application = Application::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['examination.examinationSlots.rooms'])
            ->firstOrFail();
    }

    public function goBack()
    {
        return redirect()->route('applicant.applications');
    }

    public function render()
    {
        return view('livewire.applicant-application-view')
            ->layout('components.layouts.applicant');
    }
}
