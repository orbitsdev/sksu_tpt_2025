<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\HonorOrAwardsReceivedRelations;
class HonorOrAwardsReceived extends Model
{
    use HonorOrAwardsReceivedRelations;
     protected $fillable = [
        'application_id',
        'title',
    ];
}
