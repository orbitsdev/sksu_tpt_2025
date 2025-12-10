<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ApplicationInformationRelations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ApplicationInformation extends Model implements HasMedia
{
    use ApplicationInformationRelations;
    use InteractsWithMedia;
    protected $fillable = [
        'application_id',
        'type',
        'first_name',
        'last_name',
        'middle_name',
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
            $this->middle_name,
            $this->last_name,
            $this->extension,
        ]);

        return implode(' ', $parts);
    }

    /*
    |--------------------------------------------------------------------------
    | Media Library
    |--------------------------------------------------------------------------
    */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);
    }
}
