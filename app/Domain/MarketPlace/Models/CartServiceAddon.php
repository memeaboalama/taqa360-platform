<?php

namespace App\Domain\MarketPlace\Models;

use Domain\MarketPlace\Models\MerchantAddon;
use Domain\MarketPlace\Models\MerchantAddonCategory;
use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CartServiceAddon extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id',
        'merchant_service_id',
        'cart_service_id',
        'addon_category_id',
        'service_addon_id',
        'quantity',
    ];



    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantService::class,'merchant_service_id','id');
    }
    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cart::class,'cart_id','id');
    }
    public function cart_service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CartService::class,'cart_service_id','id');
    }
    public function addon_category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantAddonCategory::class,'addon_category_id','id');
    }
    public function addon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantAddon::class,'service_addon_id','id');
    }
    public function getSubtotalAttribute(): float
    {
        return $this->addon?->price * $this->quantity;
    }
    public function getNameAttribute(): string
    {
        return $this->addon?->title;
    }
}
