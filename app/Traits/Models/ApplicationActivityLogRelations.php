<?php
namespace App\Traits\Models;

use App\Models\Application;

trait ApplicationActivityLogRelations
{

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    



}
