<?php
namespace App\Traits\Models;

use App\Models\Campus;
use App\Models\ExaminationSlot;

trait TestCenterRelations
{
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function examinationSlots()
    {
        return $this->hasMany(ExaminationSlot::class);
    }
}
