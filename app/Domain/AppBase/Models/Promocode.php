<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\Promocode\PromocodeStatus;
use Domain\MarketPlace\Models\Merchant;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;
    protected $fillable=[
        'code',
        'usages_left',
        'limit_per_user',
        'min_order_value',
        'max_discount',
        'period',
        'type',
        'source',
        'type_discount',
        'discount_value',
        'status',
        "sales_agent_id",
        "merchant_id",
        "is_custom",
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'usages_left' => 'integer',
        'limit_per_user' => 'integer',
        'Period' => 'integer',
        'status' => PromocodeStatus::class,
    ];

    public function sales_agent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesAgent::class, 'sales_agent_id', 'id');
    }
    public function promocodeMerchantsChoice(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PromocodeMerchantsChoice::class, 'promocode_id', 'id');
    }

    public function appliesToMerchant($merchantId): bool
    {
        if (!$this->is_custom) {
            return true;
        }

        if (!$merchantId) {
            return false;
        }

        if ((int) $this->merchant_id === (int) $merchantId) {
            return true;
        }

        return $this->promocodeMerchantsChoice()
            ->where('merchant_id', $merchantId)
            ->exists();
    }

    public function merchants(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
    
}
