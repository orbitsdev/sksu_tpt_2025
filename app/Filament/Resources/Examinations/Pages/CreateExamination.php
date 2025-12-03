<?php

namespace App\Filament\Resources\Examinations\Pages;

use App\Filament\Resources\Examinations\ExaminationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExamination extends CreateRecord
{
    protected static string $resource = ExaminationResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
