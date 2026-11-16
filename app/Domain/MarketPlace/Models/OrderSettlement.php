<?php

namespace App\Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSettlement extends Model
{
    protected $fillable = [
        'order_id',
        'driver_id',
        'recipient',
        'method',
        'recipient_name',
        'instructions',
        'account_data',
        'amount',
        'reference',
        'proof_path',
        'status',
        'received_amount',
        'received_at',
        'received_by',
        'receipt_confirmed',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'received_at' => 'datetime',
        'receipt_confirmed' => 'boolean',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public static function requiresProof(string $method): bool
    {
        return in_array($method, ['transfer', 'deposit'], true);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class, 'order_id');
    }

    public function corrections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderSettlementCorrection::class, 'settlement_id');
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Domain\Driver\Models\Driver::class, 'driver_id');
    }

    public function histories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderSettlementHistory::class, 'settlement_id')
            ->orderBy('created_at')
            ->orderBy('id');
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Domain\AppBase\Models\Payment::class, 'settlement_id');
    }
}
