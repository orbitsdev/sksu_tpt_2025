<?php

namespace App\Models;

use App\Models\ApplicationSlot;
use App\Models\ExaminationSlot;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ExaminationRoomRelations;
class ExaminationRoom extends Model
{
    use ExaminationRoomRelations;


}
