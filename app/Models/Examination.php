<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ExaminationRelations;

class Examination extends Model
{
    use ExaminationRelations;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'is_published',
        'is_application_open',
        'show_result',
        'school_year',
        'type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
        'is_application_open' => 'boolean',
        'show_result' => 'boolean',
    ];
}
