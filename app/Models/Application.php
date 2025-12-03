<?php

namespace App\Models;

use App\Traits\Models\ApplicationRelations;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use ApplicationRelations;

    protected $fillable = [
        'examination_id',
        'user_id',
        'step',
        'exam_number',
        'examinee_number',
        'permit_number',
        'permit_issued_at',
        'status',
    ];

    protected $casts = [
        'permit_issued_at' => 'datetime',
        'exam_number' => 'integer',
        'step' => 'integer',
    ];
}
