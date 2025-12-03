<?php
namespace App\Traits\Models;

use App\Models\User;

trait PersonalInformationRelations
{
    public function user(){
        return $this->belongsTo(User::class);
    }
}
