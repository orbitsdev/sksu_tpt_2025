<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ApplicationInformationRelations;

class ApplicationInformation extends Model
{
    use ApplicationInformationRelations;
    protected $fillable = [
        'application_id',
        'type',
        'first_name',
        'last_name',
        'extension',
        'present_address',
        'permanent_address',
        'contact_number',
        'date_of_birth',
        'place_of_birth',
        'tribe',
        'religion',
        'nationality',
        'citizenship',
        'photo',
        'sex',
        'school_graduated',
        'year_graduated',
        'school_last_attended',
        'year_last_attended',
        'previous_school_address',
        'track_and_strand_taken',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    protected $appends = [
        'full_name',
    ];

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->last_name,
            $this->extension,
        ]);

        return implode(' ', $parts);
    }
}
