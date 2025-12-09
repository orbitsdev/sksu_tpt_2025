<?php
namespace App\Traits\Models;
use App\Models\Application;
use App\Models\ApplicationInformation;

trait HonorOrAwardsReceivedRelations {
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function info()
    {
        return $this->belongsTo(ApplicationInformation::class, 'application_id');
    }
}
