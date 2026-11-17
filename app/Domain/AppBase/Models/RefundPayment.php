<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\RefundPayment\RefundPaymentStatus;
use Domain\AppBase\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPayment extends Model
{
    use HasFactory;
    protected $fillable=[
        'refund_payment_id',
        'amount',
        'currency',
        'note',
        'type',
        'payment_id',
        'order_id',
        'user_id',
        'user_name',
        'calculated_on',
        'funded_by',
        'driver_id',
        'status',
        'refund_method',
        'collection_payment_record_id',
        'settlement_id',
        'requested_by',
        'requested_at',
        'executed_by',
        'executed_at',
        'reference',
        'proof_path',
    ];

    // protected $casts = [
    //     'status' => RefundPaymentStatus::class,
    // ];

    protected $casts = [
        'requested_at' => 'datetime',
        'executed_at' => 'datetime',
    ];

    public function payment(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function collectionPayment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Payment::class, 'collection_payment_record_id');
    }

    public function settlement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Domain\MarketPlace\Models\OrderSettlement::class, 'settlement_id');
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Domain\MarketPlace\Models\MerchantOrder::class, 'order_id');
    }

    public function requestedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Domain\Admin\Models\Admin::class, 'requested_by');
    }

    public function executedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Domain\Admin\Models\Admin::class, 'executed_by');
    }

    public function isExecuted(): bool
    {
        return $this->status === 'executed' || $this->status === null;
    }

    public function payable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
    
}
