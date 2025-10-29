<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;

class CapacitySummary extends Column
{
    protected string $view = 'filament.tables.columns.capacity-summary';

     public function getCapacityData(): array
    {
        $record = $this->getRecord();
        $capacity = $record->total_capacity ?? 0;
        $occupied = $record->total_occupied ?? 0;
        $left = max($capacity - $occupied, 0);
        $percent = $capacity > 0 ? round(($occupied / $capacity) * 100) : 0;

        return compact('capacity', 'occupied', 'left', 'percent');
    }
}
