<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ExaminationSlotRelations;
class ExaminationSlot extends Model
{
  use ExaminationSlotRelations;

    // helpers
    public function getTotalCapacityAttribute()
    {
        return $this->rooms->sum('capacity');
    }

    public function getAvailableAttribute()
    {
        return $this->total_capacity - $this->total_occupied;
    }
}
