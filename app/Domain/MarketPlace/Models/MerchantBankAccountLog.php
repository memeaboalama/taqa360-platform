<?php

namespace App\Domain\MarketPlace\Models;

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantBankAccountLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'merchant_id',
        'actor_type',
        'actor_id',
        'action',
        'notes',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(MerchantBankAccountRequest::class, 'request_id');
    }

    public function getActorNameAttribute(): string
    {
        if ($this->actor_type === 'admin' && $this->actor_id) {
            $admin = Admin::find($this->actor_id);
            return $admin?->name ?? ('Admin #' . $this->actor_id);
        }

        if ($this->actor_type === 'merchant' && $this->actor_id) {
            $merchant = Merchant::find($this->actor_id);
            return $merchant?->company_name ?: ($merchant?->name ?: ('Merchant #' . $this->actor_id));
        }

        return 'System';
    }
}
