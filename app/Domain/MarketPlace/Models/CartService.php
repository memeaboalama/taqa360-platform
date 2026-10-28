<?php

namespace App\Domain\MarketPlace\Models;

use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CartService extends Model
{
    use HasFactory;
    protected $fillable = [
        'note',
        'cart_id',
        'merchant_service_id',
        'quantity',
    ];

    protected $casts = [
        'total_addons' => 'float',
        'subtotal' => 'float'
    ];
    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantService::class,'merchant_service_id','id');
    }
    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cart::class,'cart_id','id');
    }
    public function addons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartServiceAddon::class,'cart_service_id','id');
    }

    public function getNameAttribute()
    {
        return $this->service?->title;
    }


    public function getPriceAttribute(): float
    {
        return $this->service->default_price;
    }
    public function getWorkingMinutesAttribute(): int
    {
        return $this->service->working_minutes;
    }

    public function getSubtotalAttribute(): float
    {
        return (($this->service?->default_price) * $this->quantity) + $this->total_addons;
    }

    protected function totalAddons(): Attribute
    {
        return new Attribute(
            get: fn() => $this->addons->reduce(function ($value, $item) {
                return $value + ($item->subtotal) * $this->quantity;
            }, 0)
        );
    }
}
