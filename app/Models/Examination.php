<?php

namespace App\Models;

use App\Models\Application;
use App\Models\ExaminationSlot;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    public function application(){
        return $this->hasMany(Application::class);
    }

    public function examinationSlots(){
        return $this->hasMany(ExaminationSlot::class);
    }
    public function examination_slots(){
        return $this->hasMany(ExaminationSlot::class);
    }
}
