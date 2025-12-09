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
        'email',
        'contact_number',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function getFullNameAttribute()
    {
        $name = "{$this->first_name}";
        if ($this->middle_name) {
            $name .= " {$this->middle_name}";
        }
        $name .= " {$this->last_name}";
        if ($this->suffix) {
            $name .= " {$this->suffix}";
        }
        return trim($name);
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}
