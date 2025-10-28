<?php

namespace App\Models;

use App\Models\ApplicationSlot;
use App\Models\ExaminationSlot;
use Illuminate\Database\Eloquent\Model;

class ExaminationRoom extends Model
{

public function slot()
{
    return $this->belongsTo(ExaminationSlot::class, 'examination_slot_id');
}

public function applicationSlots()
{
    return $this->hasMany(ApplicationSlot::class, 'examination_room_id');
}

}
