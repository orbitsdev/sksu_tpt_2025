<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;

class CapacitySummary extends Column
{
    protected string $view = 'filament.tables.columns.capacity-summary';

    public function getCapacityData(): array
    {
        $record = $this->getRecord();

        // ✅ Compute totals dynamically from relationships
        $capacity = $record->examinationSlots?->flatMap(fn ($slot) => $slot->rooms)->sum('capacity') ?? 0;
        $occupied = $record->examinationSlots?->flatMap(fn ($slot) => $slot->rooms)->sum('occupied') ?? 0;

        $left = max($capacity - $occupied, 0);
        $percent = $capacity > 0 ? round(($occupied / $capacity) * 100) : 0;

        return compact('capacity', 'occupied', 'left', 'percent');
    }
}
