<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Traits\Models\ApplicationActivityLogRelations;
class ApplicationActivityLog extends Model
{
    use ApplicationActivityLogRelations;
    //
}
