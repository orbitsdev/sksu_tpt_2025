<?php

namespace App\Models;

use App\Models\Program;
use App\Models\ExaminationSlot;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\CampusRelations;

class Campus extends Model
{
    use CampusRelations;

    protected $fillable = [
        'name',
        'type',
        'address',
    ];
}
