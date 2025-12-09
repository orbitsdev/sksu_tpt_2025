<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get student's current applications
        $myApplications = Application::where('user_id', $user->id)
            ->with(['examination.examinationSlots.rooms'])
            ->latest()
            ->get();

        // Get active examinations (published and accepting applications)
        $activeExaminations = Examination::where('is_public', true)
            ->where('application_open', true)
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
        $upcomingExaminations = Examination::where('is_public', true)
            ->where('application_open', false)
            ->with(['examinationSlots'])
            ->latest()
            ->get();

        return view('student.dashboard', compact(
            'myApplications',
            'activeExaminations',
            'upcomingExaminations'
        ));
    }
}
