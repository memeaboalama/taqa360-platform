<?php

namespace App\Domain\AppBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Domain\Admin\Models\Admin;
use Domain\Educator\Models\Educator;

class NotificationNew extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'channel',
        'device_type',
        'educator_id',
        'is_read',
        'admin_id'

    ];

    protected $casts = [
        'is_read' => 'boolean',
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
