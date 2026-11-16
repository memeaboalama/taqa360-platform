<?php

namespace App\Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderSettlementHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'settlement_id',
        'actor_type',
        'actor_id',
        'action',
        'status_from',
        'status_to',
        'note',
        'snapshot',
        'created_at',
    ];

    protected $casts = [
        'snapshot' => 'array',
        'created_at' => 'datetime',
    ];

    public function settlement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderSettlement::class, 'settlement_id');
    }

    public function actor(): MorphTo
    {
        return $this->morphTo();
    }
}
