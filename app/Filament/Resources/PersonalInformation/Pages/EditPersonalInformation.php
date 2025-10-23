<?php

namespace App\Filament\Resources\PersonalInformation\Pages;

use App\Filament\Resources\PersonalInformation\PersonalInformationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonalInformation extends EditRecord
{
    protected static string $resource = PersonalInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
