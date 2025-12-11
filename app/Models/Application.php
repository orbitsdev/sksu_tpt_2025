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
        'previous_step',
        'current_step',
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
        'previous_step' => 'integer',
        'current_step' => 'integer',
    ];

     protected $appends = [
        'has_permit',
        'is_finalized',
        'status',
    ];

    public function getHasPermitAttribute(): bool
    {
        return !empty($this->permit_number);
    }

    public function getIsFinalizedAttribute(): bool
    {
        return !is_null($this->finalized_at);
    }

    /**
     * Get status based on current_step for backward compatibility with views
     */
    public function getStatusAttribute(): string
    {
        if ($this->current_step == 58) {
            return 'rejected';
        } elseif ($this->current_step >= 70) {
            return 'approved';
        } else {
            return 'pending';
        }
    }

    public function issuePermit(string $permitNumber): void
    {
        $this->update([
            'permit_number' => $permitNumber,
            'permit_issued_at' => now(),
            'previous_step' => $this->current_step,
            'current_step' => 70,
            'step_description' => 'Approved - Select Exam Slot',
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'previous_step' => $this->current_step,
            'current_step' => 100,
            'step_description' => 'Admission Decision Finalized',
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
