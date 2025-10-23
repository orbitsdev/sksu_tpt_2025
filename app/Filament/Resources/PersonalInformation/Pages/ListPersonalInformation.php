<?php

namespace App\Filament\Resources\PersonalInformation\Pages;

use App\Filament\Resources\PersonalInformation\PersonalInformationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonalInformation extends ListRecords
{
    protected static string $resource = PersonalInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
