<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\Promocode\PromocodeStatus;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletCoupon extends Model
{
    use HasFactory;
    protected $fillable=[
        'code',
        'usages_left',
        'limit_per_user',
        'min_order_value',
        'max_amount',
        'type',
        'type_amount',
        'amount_value',
        'status',
        "sales_agent_id",
        "is_custom",
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'usages_left' => 'integer',
        'limit_per_user' => 'integer',
        'status' => PromocodeStatus::class,
    ];

    public function sales_agent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesAgent::class, 'sales_agent_id', 'id');
    }
    public function walletCouponMerchants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletCouponMerchants::class, 'wallet_coupon_id', 'id');
    }
    
}
