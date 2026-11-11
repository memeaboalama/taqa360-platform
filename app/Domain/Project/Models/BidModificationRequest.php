<?php

namespace Domain\Project\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BidModificationRequest extends Model
{
    use HasUuid;

    protected $fillable = [
        'bid_id',
        'educator_id',
        'status',
        'previous_values',
        'requested_values',
        'decision_note',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'previous_values' => 'array',
        'requested_values' => 'array',
        'decided_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }
}
