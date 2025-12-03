<?php
namespace App\Traits\Models;

use App\Models\ApplicationSlot;
use App\Models\Campus;
use App\Models\Examination;
use App\Models\ExaminationRoom;
use App\Models\TestCenter;

trait ExaminationSlotRelations {
      public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function testCenter()
    {
        return $this->belongsTo(TestCenter::class);
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
