<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\PersonalInformationRelations;

class PersonalInformation extends Model
{
    use PersonalInformationRelations;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'sex',
        'birth_date',
        'birth_place',
        'civil_status',
        'nationality',
        'religion',
        'email',
        'contact_number',
        'house_no',
        'street',
        'barangay',
        'municipality',
        'province',
        'region',
        'zip_code',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];


    public function getFullAddressAttribute()
    {
        return trim("{$this->house_no} {$this->street}, {$this->barangay}, {$this->municipality}, {$this->province}, {$this->zip_code}");
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}
