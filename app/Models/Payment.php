<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\PaymentRelations;
class Payment extends Model
{
    use PaymentRelations;
     protected $fillable = [
        'application_id',
        'examination_id',
        'applicant_id',
        'cashier_id',
        'campus_id',
        'amount',
        'amount_paid',
        'change',
        'payment_method',
        'payment_channel',
        'payment_reference',
        'official_receipt_number',
        'receipt_file',
        'status',
        'paid_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    protected $appends = [
        'is_verified',
        'is_pending',
        'is_rejected',
        'is_refunded',
    ];


    public function getIsVerifiedAttribute(): bool
    {
        return $this->status === 'VERIFIED';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'PENDING';
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'REJECTED';
    }

    public function getIsRefundedAttribute(): bool
    {
        return $this->status === 'REFUNDED';
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function markAsPaid(): void
    {
        $this->update([
            'paid_at' => now(),
            'status' => 'PENDING',
        ]);
    }

    public function verify(int $staffId): void
    {
        $this->update([
            'verified_by' => $staffId,
            'verified_at' => now(),
            'status' => 'VERIFIED',
        ]);
    }

    public function reject(string $reason = null): void
    {
        $this->update([
            'status' => 'REJECTED',
        ]);
    }
}
