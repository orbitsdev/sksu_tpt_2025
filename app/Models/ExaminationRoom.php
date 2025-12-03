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

    protected $appends = ['capacity', 'occupied', 'available'];

    /**
     * Computed: Room capacity based on slot's total examinees divided by number of rooms
     * This ensures capacity is always accurate and never outdated
     */
    public function getCapacityAttribute(): int
    {
        if (!$this->examinationSlot) {
            return 0;
        }

        return (int) ceil(
            $this->examinationSlot->total_examinees / $this->examinationSlot->number_of_rooms
        );
    }

    /**
     * Computed: Number of examinees currently assigned to this room
     * Counts from application_slots (single source of truth)
     */
    public function getOccupiedAttribute(): int
    {
        return $this->applicationSlots()->count();
    }

    /**
     * Computed: Available slots in this room
     */
    public function getAvailableAttribute(): int
    {
        return max(0, $this->capacity - $this->occupied);
    }

    /**
     * Check if room is full
     */
    public function isFull(): bool
    {
        return $this->occupied >= $this->capacity;
    }
}
