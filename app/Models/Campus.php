<?php

namespace App\Models;


use App\Traits\Models\CampusRelations;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use CampusRelations;

    protected $fillable = [
        'name',
        'address',
        'type',
        'contact_email',
        'contact_number',
    ];

}
