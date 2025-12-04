<?php

use App\Livewire\ApplicantDashboard;
use App\Livewire\ApplicantApplications;
use App\Livewire\ApplicantApplicationView;
use App\Livewire\ApplicantExaminations;
use Illuminate\Support\Facades\Route;

/**
 * Applicant Routes
 *
 * All routes for applicant/student functionality
 * Protected by 'auth' and 'role:student' middleware
 */
Route::middleware(['auth', 'role:student'])->prefix('applicant')->name('applicant.')->group(function () {

    // Dashboard
    Route::get('/dashboard', ApplicantDashboard::class)->name('dashboard');

    // Applications - View and manage applications
    Route::get('/applications', ApplicantApplications::class)->name('applications');
    Route::get('/application/{id}', ApplicantApplicationView::class)->name('application.view');

    // Examinations - Browse available examinations
    Route::get('/examinations', ApplicantExaminations::class)->name('examinations');

    // TODO: Application creation route
    // Route::get('/application/create/{examination}', ApplicantApplicationCreate::class)->name('application.create');

});
