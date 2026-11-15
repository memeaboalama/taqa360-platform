<?php

namespace App\Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\Admin\Models\Admin;

class OrderSettlementCorrection extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'corrected_by',
        'amount',
        'reason',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'corrected_by');
    }
}
