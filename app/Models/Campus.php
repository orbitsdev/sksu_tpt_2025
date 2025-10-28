<?php

namespace App\Models;

use App\Models\Program;
use App\Models\ExaminationSlot;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    public function programs(){
        return $this->hasMany(Program::class);
    }

    public function examinationSlots(){
        return $this->hasMany(ExaminationSlot::class);
    }
}
