<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\Offer\OfferStatus;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
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
        'type_discount',
        'discount_value',
        'status',
        "note",
        "merchant_id",
        "is_custom",
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'usages_left' => 'integer',
        'limit_per_user' => 'integer',
        'Period' => 'integer',
        'status' => OfferStatus::class,
    ];
    public function merchants(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
    
}
