<?php

namespace App\Filament\Resources\TestCenters\Pages;

use App\Filament\Resources\TestCenters\TestCenterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTestCenter extends ViewRecord
{
    protected static string $resource = TestCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
