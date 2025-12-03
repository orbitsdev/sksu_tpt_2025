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
}
