<?php

namespace App\Domain\AppBase\Models;

use App\Domain\User\Models\User;
use App\Support\Enum\Promocode\PromocodeStatus;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletCouponsRecharges extends Model
{
    use HasFactory;
    protected $table = "wallet_coupons_recharges";
    protected $fillable=[
        'user_id',
        'wallet_coupon_id',
        'wallet_type',
        'wallet_id',
    ];



    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function walletCoupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WalletCoupon::class, 'wallet_coupon_id', 'id');
    }
    
}
