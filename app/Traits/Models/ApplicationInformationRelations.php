<?php
namespace App\Traits\Models;
use App\Models\Application;
use App\Models\HonorOrAwardsReceived;

trait ApplicationInformationRelations {
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function honors()
    {
        return $this->hasMany(HonorOrAwardsReceived::class, 'application_id');
    }
}
