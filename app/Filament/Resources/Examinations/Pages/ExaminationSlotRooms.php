<?php

namespace App\Filament\Resources\Examinations\Pages;

use App\Models\ExaminationSlot;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Filament\Resources\Examinations\ExaminationResource;


class ExaminationSlotRooms extends Page
{
    use InteractsWithRecord;

    // ✅ Keep this the same because it still belongs under the Examination resource
    protected static string $resource = ExaminationResource::class;

    protected string $view = 'filament.resources.examinations.pages.examination-slot-rooms';
    protected ?string $subheading = 'Custom Page Subheading';
    public ExaminationSlot $slot;
    public function getBreadcrumbs(): array
{
    return [];
}


    public function mount(int|string $record): void
    {
        // ⚠️ Explicitly resolve an ExaminationSlot instead of Examination
        $this->slot = ExaminationSlot::with(['campus', 'rooms'])
            ->findOrFail($record);
    }

}
