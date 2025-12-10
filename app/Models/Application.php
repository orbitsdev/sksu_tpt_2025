<?php

namespace App\Models;

use App\Traits\Models\ApplicationRelations;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Application extends Model implements HasMedia
{
    use ApplicationRelations;
    use InteractsWithMedia;

    protected $fillable = [
        'examination_id',
        'user_id',
        'status',
        'step',
        'step_description',
        'examinee_number',
        'permit_number',
        'permit_issued_at',
        'first_priority_program_id',
        'second_priority_program_id',
        'final_program_id',
        'finalized_at',
    ];

    protected $casts = [
        'permit_issued_at' => 'datetime',
        'finalized_at' => 'datetime',
        'step' => 'integer',
    ];

     protected $appends = [
        'has_permit',
        'is_finalized',
    ];

    public function getHasPermitAttribute(): bool
    {
        return !empty($this->permit_number);
    }

    public function getIsFinalizedAttribute(): bool
    {
        return !is_null($this->finalized_at);
    }

    public function issuePermit(string $permitNumber): void
    {
        $this->update([
            'permit_number' => $permitNumber,
            'permit_issued_at' => now(),
            'status' => 'PERMIT_ISSUED',
            'step' => 4,
            'step_description' => 'Permit Issued',
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'COMPLETED',
            'finalized_at' => now(),
        ]);
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
