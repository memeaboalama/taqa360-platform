<?php

namespace App\Domain\Ecommerce\Models;

use App\Domain\MarketPlace\Models\ItemPrices;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\CartChoice;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebCartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'cart_id',
        //        'additional',
        //        'additional_total',
        //        'item_price',
        'qty',
        //        'total',
    ];

    protected $casts = [
        'total_choices' => 'float',
        'item_price' => 'float',
        'subtotal' => 'float'
    ];

    protected $appends = [
        'uniqueId'
    ];

    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WebCart::class, 'cart_id', 'id');
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function getAdditionalAttribute($value)
    {
        return explode(',', $value);
    }
    public function cartChoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartChoice::class, 'cart_item_id', 'id');
    }
    public function setAdditionalAttribute($value)
    {
        $this->attributes['additional'] = implode(",", $value);
    }

    ////////



    public function getUniqueIdAttribute(): array|string
    {
        if ($this->cartChoices()->exists()) {
            foreach ($this->cartChoices as $choice) $data[] = $this->item?->id . "-" . $choice->uniqueId;
            return $data;
        } else {
            $string = (string)$this->item?->id;
            return $string;
        }
    }


    public function itemTypeServicePrice(): Attribute
    {
        if (!$this->item) {
            return new Attribute(get: fn() => null);
        }
        $item_service_type_price = ItemPrices::query()
            ->where('item_id', $this->item->id)
            ->where('type_service_id', $this->cart->service_type_id)
            ->where('status', StatusEnum::ACTIVE)
            // ->select('price')
            ->first();
        return new Attribute(
            get: fn() => $item_service_type_price
        );
    }

    public function itemPrice(): Attribute
    {
        if ($this->cart && isset($this->cart->service_type_id)) {
            if (isset($this->item_id) && $this->item)
                $item_service_type_price = ItemPrices::query()
                    ->where('item_id', $this->item->id)
                    ->where('type_service_id', $this->cart->service_type_id)
                    ->where('status', StatusEnum::ACTIVE)
                    // ->select('price')
                    ->first();
            else if ($this->item && isset($this->item->id))
                $item_service_type_price = ItemPrices::query()
                    ->where('item_id', $this->item->id)
                    ->where('type_service_id', $this->cart->service_type_id)
                    ->where('status', StatusEnum::ACTIVE)
                    // ->select('price')
                    ->first();
        }
        $service_price = $item_service_type_price?->price ?? $this->item?->default_price ?? 0.0;
        return new Attribute(
            get: fn() => (float)$service_price
        );
    }

    public function getPriceAttribute(): float
    {
        return $this->item?->default_price ?? 0.0;
    }

    // public function getSubtotalAttribute(): float
    // {
    //     return (($this->item?->default_price ?? 0.0) * $this->qty) + $this->total_choices;
    // }
    public function getSubtotalAttribute(): float
    {
        return (($this->item?->default_price ?? 0.0) * $this->qty) + $this->total_choices;
    }

    protected function totalChoices(): Attribute
    {
        return new Attribute(
            get: fn() => $this->cartChoices->reduce(function ($value, $item) {
                return $value + ($item->subtotal) * $this->qty;
            }, 0)
        );
    }
    public static function boot()
    {
        parent::boot();
        self::deleting(function ($cartItem) { // before delete() method call this
            $cartItem->cartChoices()->delete();
        });
    }
}
