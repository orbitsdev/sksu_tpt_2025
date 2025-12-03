<?php
namespace App\Traits\Models;

use App\Models\ApplicationSlot;
use App\Models\Campus;
use App\Models\Examination;
use App\Models\ExaminationRoom;

trait ExaminationSlotRelations {
      public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function rooms()
    {
        return $this->hasMany(ExaminationRoom::class);
    }

    public function applicationSlots()
    {
        return $this->hasMany(ApplicationSlot::class);
    }
}
