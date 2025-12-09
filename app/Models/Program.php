<?php

namespace App\Models;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ProgramRelations;

class Program extends Model
{
    use ProgramRelations;

    protected $fillable = [
        'campus_id',
        'name',
        'abbreviation',
        'code',
        'is_offered',
    ];

    protected $casts = [
        'is_offered' => 'boolean',
    ];
}
