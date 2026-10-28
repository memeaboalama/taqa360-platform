<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\CashBack\CashBackStatus;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBack extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'usages_left',
        'limit_per_user',
        'min_order_value',
        'max_discount',
        'period',
        'type',
        'service_type_id',
        'discount_value',
        'status',
        "merchant_id",
        "is_custom",
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'usages_left' => 'integer',
        'limit_per_user' => 'integer',
        'status' => CashBackStatus::class,
    ];
    public function getTypeServiceNameAttribute(): string
    {
        $typeService = TypeService::find($this->service_type_id);
        return $typeService ? $typeService->name : '';
    }
    public function cashBackMerchantsChoice(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CashBackMerchantsChoice::class, 'cashback_id', 'id');
    }

    public function merchants(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
    
}
