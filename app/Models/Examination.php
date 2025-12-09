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
        'is_public',
        'application_open',
        'school_year',
        'exam_type',
        'is_results_published',
        'application_start_date',
        'application_end_date',
        'results_published_at',
        'results_release_at',
    ];

   protected $casts = [
    'is_public' => 'boolean',
    'application_open' => 'boolean',
    'is_results_published' => 'boolean',

    'start_date' => 'date',
    'end_date' => 'date',
    'application_start_date' => 'date',
    'application_end_date' => 'date',
'results_published_at' => 'datetime',
'results_release_at' => 'date',

];

}
