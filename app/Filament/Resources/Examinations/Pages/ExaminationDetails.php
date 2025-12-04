<?php

namespace App\Filament\Resources\Examinations\Pages;

use App\Filament\Resources\Examinations\ExaminationResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ExaminationDetails extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ExaminationResource::class;

    protected string $view = 'filament.resources.examinations.pages.examination-details';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
