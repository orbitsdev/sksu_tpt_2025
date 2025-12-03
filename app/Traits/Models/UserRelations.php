<?php
namespace App\Traits\Models;

use App\Models\PersonalInformation;



trait UserRelations
{
    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class);
    }
}
