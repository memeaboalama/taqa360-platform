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
    ];

    public function requestedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'requested_by');
    }
}
