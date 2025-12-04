<?php

namespace App\Filament\Dashboard\Pages\Auth;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (session()->has('intended_url')) {
            $intendedUrl = session()->pull('intended_url');
            return redirect()->to($intendedUrl);
        }

        $user = Auth::user();

        // Role-based redirection
        if ($user->hasRole('admin')) {
           return redirect()->route('filament.admin.pages.dashboard');
        }

        if ($user->hasRole('staff')) {
            return redirect()->route('staff.dashboard');
        }

        if ($user->hasRole('student')) {
            return redirect()->route('applicant.dashboard');
        }

        // Fallback to default dashboard
        return redirect()->intended(filament()->getUrl());
    }
}
