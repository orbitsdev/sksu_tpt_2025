<?php

use Laravel\Fortify\Features;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

/**
 * Public Routes
 */
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * Admin Routes
 * TODO: Move to routes/admin.php
 */
Route::get('/admin-dashboard',function(){
    return 'test dashobard ';
})->name('admin-dashboard');

/**
 * Staff Routes
 * TODO: Move to routes/staff.php
 */
Route::get('/staff/dashboard', function () {
    return 'staff dashobard ';
})->name('staff.dashboard');

/**
 * Dashboard Route - Role-based redirection
 * Redirects users to their appropriate dashboard based on role
 */
Route::get('dashboard', function(){
    $user = auth()->user();

    // Redirect based on role
    if ($user->hasRole('admin')) {
        return redirect()->route('filament.admin.pages.dashboard');
    }

    if ($user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    }

    if ($user->hasRole('student')) {
        return redirect()->route('applicant.dashboard');
    }

    // Fallback for users without roles
    return redirect()->route('home');
})
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
