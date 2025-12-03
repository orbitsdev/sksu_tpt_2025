<?php
namespace App\Traits\Models;
use App\Models\ApplicationSlot;
use App\Models\Examination;

trait  ApplicationRelations{


    public function examination(){
        return $this->belongsTo(Examination::class);
    }

    public function slot()
{
    return $this->hasOne(ApplicationSlot::class);
}
}
