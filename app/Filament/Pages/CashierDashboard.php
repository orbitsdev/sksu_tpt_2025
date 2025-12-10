<?php

namespace App\Filament\Pages;




use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Pages\Page;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

use UnitEnum;
use BackedEnum;

class CashierDashboard extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected string $view = 'filament.pages.cashier-dashboard';

    protected static string|UnitEnum|null $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 1;

public static function getNavigationLabel(): string
{
    return 'Cashier Transaction';
}

    public function getHeading(): string
    {
        return '';
    }

    public function mount(): void
    {

    }

}
