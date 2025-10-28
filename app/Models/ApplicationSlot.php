<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSlot extends Model
{
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
