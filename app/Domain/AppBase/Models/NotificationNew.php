<?php

namespace App\Domain\AppBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Domain\Admin\Models\Admin;
use Domain\Educator\Models\Educator;

class NotificationNew extends Model
{
    use HasFactory;

    public const DELIVERY_PENDING = 'pending';
    public const DELIVERY_SENT = 'sent';
    public const DELIVERY_PARTIAL = 'partial';
    public const DELIVERY_FAILED = 'failed';
    public const DELIVERY_NO_RECIPIENTS = 'no_recipients';
    public const DELIVERY_UNKNOWN = 'unknown';

    protected $fillable = [
        'title',
        'body',
        'channel',
        'device_type',
        'educator_id',
        'is_read',
        'admin_id',
        'delivery_status',
        'sent_count',
        'failed_count',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
    ];

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function educator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Educator::class, 'educator_id', 'id');
    }

}
