<?php
namespace App\Traits\Models;

use App\Models\Program;
use App\Models\TestCenter;

trait CampusRelations {

    public function programs(){
        return $this->hasMany(Program::class);
    }

    public function testCenters(){
        return $this->belongToMany(TestCenter::class);
    }
}
