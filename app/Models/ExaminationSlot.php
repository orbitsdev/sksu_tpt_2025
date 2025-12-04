<?php

namespace App\Models;

use App\Traits\Models\ExaminationSlotRelations;
use Illuminate\Database\Eloquent\Model;

class ExaminationSlot extends Model
{
    use ExaminationSlotRelations;

    protected $fillable = [
        'examination_id',
        'test_center_id',
        'building_name',
        'date_of_exam',
        'total_examinees',
        'number_of_rooms',
        'is_active',
    ];

    protected $casts = [
        'date_of_exam' => 'date',
        'is_active' => 'boolean',
    ];

    // helpers
    public function getTotalCapacityAttribute()
    {
        return $this->rooms->sum('capacity');
    }

    public function getAvailableAttribute()
    {
        return $this->total_capacity - $this->total_occupied;
    }

    public function hasAssignedStudents(): bool
    {
        return $this->rooms()->whereHas('applicationSlots')->exists();
    }

    public function getAssignedStudentsCountAttribute(): int
    {
        return $this->applicationSlots()->count();
    }
}
