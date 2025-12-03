<?php
namespace App\Traits\Models;
use App\Models\ExaminationSlot;
use App\Models\Program;

trait CampusRelations {

    public function programs(){
        return $this->hasMany(Program::class);
    }

    public function examinationSlots(){
        return $this->hasMany(ExaminationSlot::class);
    }
}
