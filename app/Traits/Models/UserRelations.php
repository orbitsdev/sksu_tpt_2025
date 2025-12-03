<?php
namespace App\Traits\Models;

use App\Models\Application;
use App\Models\PersonalInformation;

trait UserRelations
{
    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
