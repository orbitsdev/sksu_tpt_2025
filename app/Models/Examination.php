<?php

namespace App\Models;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    public function application(){
        return $this->hasMany(Application::class);
    }
}
