<?php
namespace App\Traits\Models;
use App\Models\ApplicationSlot;
use App\Models\ExaminationSlot;

trait ExaminationRoomRelations{
    public function slot()
{
    return $this->belongsTo(ExaminationSlot::class, 'examination_slot_id');
}

public function applicationSlots()
{
    return $this->hasMany(ApplicationSlot::class, 'examination_room_id');
}
}
