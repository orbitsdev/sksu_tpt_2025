<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login;
use Illuminate\Contracts\Support\Htmlable;

class AdminLogin extends Login
{
    protected static ?string $title = '';

    public function getHeading(): string|Htmlable
    {
        return 'SKSU TPT';
    }
}
