<?php

namespace App\Models;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
