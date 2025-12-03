<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\TestCenterRelations;

class TestCenter extends Model
{
    use TestCenterRelations;

    protected $fillable = [
        'campus_id',
        'name',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
