<?php

namespace App\Models;

use App\Models\Program;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    public function programs(){
        return $this->hasMany(Program::class);
    }
}
