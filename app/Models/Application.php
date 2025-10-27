<?php

namespace App\Models;

use App\Models\Examination;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{


    public function examination(){
        return $this->belongsTo(Examination::class);
    }
}
