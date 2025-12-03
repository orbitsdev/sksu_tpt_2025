<?php

namespace App\Traits\Models;

use App\Models\Application;
use App\Models\ExaminationSlot;

trait ExaminationRelations
{
    public function application()
    {
        return $this->hasMany(Application::class);
    }

    public function examinationSlots()
    {
        return $this->hasMany(ExaminationSlot::class);
    }
}
