<?php

namespace App\Filament\Resources\TestCenters\Pages;

use App\Filament\Resources\TestCenters\TestCenterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTestCenter extends EditRecord
{
    protected static string $resource = TestCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
