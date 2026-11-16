<?php

namespace App\Domain\MarketPlace\Models;

use Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class PaymentCorrectionRequest extends Model
{
    protected $fillable = [
        'order_id',
        'movement_type',
        'movement_id',
        'reason',
        'requested_by',
        'status',
        'original_amount',
        'corrected_amount',
        'adjustment_amount',
        'settlement_id',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'corrected_amount' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function requestedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'requested_by');
    }

    public function approvedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function cancelledBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'cancelled_by');
    }

    public function settlement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderSettlement::class, 'settlement_id');
    }
}
