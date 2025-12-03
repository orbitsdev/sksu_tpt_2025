<?php
namespace App\Traits\Models;
use App\Models\Application;
use App\Models\ExaminationRoom;
use App\Models\ExaminationSlot;

trait ApplicationSlotRelations {
     public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function examinationSlot()
    {
        return $this->belongsTo(ExaminationSlot::class);
    }

    public function examinationRoom()
    {
        return $this->belongsTo(ExaminationRoom::class);
    }
}
