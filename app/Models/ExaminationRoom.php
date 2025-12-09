<?php

namespace App\Models;

use App\Models\ApplicationSlot;
use App\Models\ExaminationSlot;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ExaminationRoomRelations;

class ExaminationRoom extends Model
{
    use ExaminationRoomRelations;

     protected $fillable = [
        'examination_slot_id',
        'room_number',
    ];

    protected $appends = [
        'capacity',
        'occupied',
        'available',
        'is_full',
    ];

    /**
     * Computed capacity:
     * total_examinees / number_of_rooms
     */
    public function getCapacityAttribute(): int
    {
        if (!$this->examinationSlot) {
            return 0;
        }

        $slot = $this->examinationSlot;

        if ($slot->number_of_rooms <= 0) {
            return 0;
        }

        return (int) ceil($slot->total_examinees / $slot->number_of_rooms);
    }

    /**
     * Computed occupancy:
     * Number of examinees assigned to this room
     */
    public function getOccupiedAttribute(): int
    {
        return $this->applicationSlots()->count();
    }

    /**
     * Computed availability:
     */
    public function getAvailableAttribute(): int
    {
        return max(0, $this->capacity - $this->occupied);
    }

    /**
     * Quick check if room is full
     */
    public function getIsFullAttribute(): bool
    {
        return $this->occupied >= $this->capacity;
    }
}
