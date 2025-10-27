<?php

namespace App\Filament\Dashboard\Pages\Auth;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as Responsable;

class LogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        // dd(Filament::getCurrentPanel()->getId());

        // if(Filament::getCurrentPanel()->getId()){
        //     return redirect()->route('home');
        // }
        // switch() {
        //     case 'admin':
        //         return redirect()->route('admin-dashboard');
        //     case 'staff':
        //         return redirect()->route('staff.dashboard');
        //     case 'student':
        //         return redirect()->route('student.dashboard');
        // }
        return redirect()->route('home');
    }
}
