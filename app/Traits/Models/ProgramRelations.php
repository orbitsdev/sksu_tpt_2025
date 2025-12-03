<?php
namespace App\Traits\Models;
use App\Models\Campus;

trait ProgramRelations {
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
