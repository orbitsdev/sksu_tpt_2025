<?php

namespace App\Filament\Resources\PersonalInformation\Pages;

use App\Filament\Resources\PersonalInformation\PersonalInformationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPersonalInformation extends ViewRecord
{
    protected static string $resource = PersonalInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
