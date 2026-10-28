<?php

namespace App\Domain\AppBase\Models;

use App\Domain\User\Models\User;
use App\Support\Enum\Promocode\PromocodeStatus;
use App\Support\Traits\Models\Payment\HasPayment;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory ,HasPayment;
    // protected $table = "wallet_transaction";
    protected $fillable=[
        'user_id',
        'type',
        'amount',
        'confirmed',
        'status',
        'meta',
        'uuid',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'int',
        'confirmed' => 'bool',
        'meta' => 'json',
    ];

    public function getAmountIntAttribute(): int
    {
        return (int) $this->amount;
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function walletCoupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WalletCoupon::class, 'wallet_coupon_id', 'id');
    }
    
}
