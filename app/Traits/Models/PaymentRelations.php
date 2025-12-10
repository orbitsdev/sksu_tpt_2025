<?php
namespace App\Traits\Models;

use App\Models\User;
use App\Models\Campus;
use App\Models\Application;
use App\Models\Examination;

trait  PaymentRelations {
    public function applicant()
{
    return $this->belongsTo(User::class, 'applicant_id');
}

public function examination()
{
    return $this->belongsTo(Examination::class);
}

public function cashier()
{
    return $this->belongsTo(User::class, 'cashier_id');
}

public function campus()
{
    return $this->belongsTo(Campus::class);
}

public function verifier()
{
    return $this->belongsTo(User::class, 'verified_by');
}

public function application()
{
    return $this->belongsTo(Application::class);
}

}
