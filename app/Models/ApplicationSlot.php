<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ApplicationSlotRelations;

class ApplicationSlot extends Model
{
    use ApplicationSlotRelations;

    protected $fillable = [
        'application_id',
        'examination_slot_id',
        'examination_room_id',
        'seat_number',
    ];

    protected $casts = [
        'seat_number' => 'integer',
    ];

    protected $appends = [
        'is_assigned',
        'seat_label',
    ];

    public function getIsAssignedAttribute(): bool
    {
        return !is_null($this->examination_room_id) && !is_null($this->seat_number);
    }

    public function getSeatLabelAttribute(): ?string
    {
        if (!$this->is_assigned) {
            return null;
        }

        return 'Seat ' . $this->seat_number;
    }


    public function assignToRoom(ExaminationRoom $room, int $seat)
    {
        $this->update([
            'examination_room_id' => $room->id,
            'seat_number' => $seat,
        ]);
    }


    public function unassign()
    {
        $this->update([
            'examination_room_id' => null,
            'seat_number' => null,
        ]);
    }


    public function inSlot(int $slotId): bool
    {
        return $this->examination_slot_id === $slotId;
    }

  
    public function canAssignToRoom(ExaminationRoom $room): bool
    {
        return $room->examination_slot_id === $this->examination_slot_id;
    }
}
