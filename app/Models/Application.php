<?php

namespace App\Models;

use App\Models\Examination;
use App\Models\ApplicationSlot;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{


    public function examination(){
        return $this->belongsTo(Examination::class);
    }

    public function slot()
{
    return $this->hasOne(ApplicationSlot::class);
}
}
