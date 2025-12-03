<?php
namespace App\Traits\Models;

use App\Models\Campus;
use App\Models\Examination;

trait TestCenterRelations
{

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }
}
