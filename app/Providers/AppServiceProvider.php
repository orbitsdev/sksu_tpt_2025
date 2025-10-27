<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use App\Filament\Dashboard\Pages\Auth\LoginResponse;
use App\Filament\Dashboard\Pages\Auth\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
          $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
         FilamentAsset::register([
         Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/custom.css'),


    ]);
    }
}
