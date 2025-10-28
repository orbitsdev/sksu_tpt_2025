<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExaminationSlot extends Model
{
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
