<?php

namespace App\Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DriverCollectionFailureReportHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'action',
        'status_from',
        'status_to',
        'note',
        'actor_type',
        'actor_id',
        'created_at',
    ];

    protected $casts = ['created_at' => 'datetime'];

    public function actor(): MorphTo
    {
        return $this->morphTo();
    }
}
