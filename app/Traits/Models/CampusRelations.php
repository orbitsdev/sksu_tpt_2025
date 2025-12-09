<?php
namespace App\Traits\Models;

use App\Models\User;
use App\Models\Program;
use App\Models\TestCenter;

trait CampusRelations {

    public function programs(){
        return $this->hasMany(Program::class);
    }

    public function testCenters(){
        return $this->hasMany(TestCenter::class);
    }


    public function users(){
        return $this->hasMany(User::class);
    }
}
