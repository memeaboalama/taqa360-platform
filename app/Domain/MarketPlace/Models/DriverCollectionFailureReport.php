<?php

namespace App\Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Model;

class DriverCollectionFailureReport extends Model
{
    protected $fillable = [
        'order_id',
        'driver_id',
        'reason',
        'driver_note',
        'status',
        'admin_instructions',
        'decided_by',
        'decided_at',
        'resolved_at',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class, 'order_id');
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Domain\Driver\Models\Driver::class, 'driver_id');
    }

    public function histories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DriverCollectionFailureReportHistory::class, 'report_id')
            ->orderBy('created_at')
            ->orderBy('id');
    }

    public function decider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Domain\Admin\Models\Admin::class, 'decided_by');
    }
}
