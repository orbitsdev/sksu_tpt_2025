<?php

namespace App\Filament\Resources\TestCenters\Pages;

use App\Filament\Resources\TestCenters\TestCenterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestCenters extends ListRecords
{
    protected static string $resource = TestCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
