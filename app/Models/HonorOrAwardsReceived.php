<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\HonorOrAwardsReceivedRelations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HonorOrAwardsReceived extends Model implements HasMedia
{
    use HonorOrAwardsReceivedRelations;
    use InteractsWithMedia;

    protected $fillable = [
        'application_id',
        'title',
    ];

    /*
    |--------------------------------------------------------------------------
    | Media Library
    |--------------------------------------------------------------------------
    */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('certificates')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']);
    }
}
