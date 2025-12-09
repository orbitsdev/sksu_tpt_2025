<?php

namespace App\Traits\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Examination;
use App\Models\ApplicationSlot;

trait  ApplicationRelations
{


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function firstPriorityProgram()
    {
        return $this->belongsTo(Program::class, 'first_priority_program_id');
    }

    public function secondPriorityProgram()
    {
        return $this->belongsTo(Program::class, 'second_priority_program_id');
    }

    public function finalProgram()
    {
        return $this->belongsTo(Program::class, 'final_program_id');
    }

    public function applicationSlot()
    {
        return $this->hasOne(ApplicationSlot::class);
    }
}
