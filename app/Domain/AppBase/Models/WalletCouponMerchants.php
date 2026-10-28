<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\Promocode\PromocodeStatus;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletCouponMerchants extends Model
{
    use HasFactory;
    protected $table = "wallet_coupon_merchants";
    protected $fillable=[
        'wallet_coupon_id',
        'merchant_id',
    ];



    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
    public function promocode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WalletCoupon::class, 'merchant_id', 'id');
    }
    
}
