<?php

namespace App\Domain\Ecommerce\Models;

use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Models\Promocode;
use App\Domain\AppBase\Models\CashBack;
use App\Domain\MarketPlace\Models\ItemPrices;
use App\Domain\MarketPlace\Models\MerchantOrderItem;
use App\Domain\User\Models\User;
use Carbon\Carbon;
use Domain\MarketPlace\Models\CartChoice;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class WebCart extends Model
{
    use HasFactory;
    use HasEvents;


    protected $fillable = [
        'user_id',
        'note',
        'promocode',
        'promocode_id',
        'cash_back',
        'cash_back_id',
        'service_type_id',
        'updated_at',
    ];


    protected $casts = [
        'subtotal' => 'float',
        'total' => 'float',
        'total_after_discount' => 'float',
        'tax' => 'float',
        'discount' => 'float',
        'delivery_fee' => 'float',
        'delivery_fee_discount' => 'float',
        'total_after_vat' => 'float',
    ];

    public $timestamps = true;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function promocode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Promocode::class, 'promocode_id', 'id');
    }
    public function cash_back(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CashBack::class, 'cash_back_id', 'id');
    }


    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Domain\MarketPlace\Models\Merchant::class, 'merchant_id', 'id');
    }

    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebCartItem::class, 'cart_id', 'id');
    }
    public function _item_prices()
    {
        $cartItems = $this->cartItems()
            // ->where('status', 0)

            ->with(['cartChoices', 'item'])
            // ->join('item_prices', function (JoinClause $join) {
            //     $join->on('item_prices.item_id', '=', 'cart_items.item_id')
            //          ->where('type_service_id.user_id', '>', $this->service_type_id);
            // })
            ->get();
        $item_ids = $cartItems->pluck('item_id')->toArray();
        $cartItemPrices = ItemPrices::Where('type_service_id', $this->service_type_id)->whereIn('item_id', $item_ids)->where('status', StatusEnum::ACTIVE)->select('item_id', 'price')->distinct();
        //->withPivot('item_id');
        foreach ($cartItems as $item) {
            $item_price = $cartItemPrices->where('item_id', $item->item_id)->first();
            if ($item_price) {
                $item->item_price = $item_price->price;
            } else {
                // $this->cartItems()->delete($item);
            }
        }
        return $cartItems;
    }
    public function itemPrices(): Attribute
    {
        return new Attribute(
            // get: fn() => $this->_item_prices()
            get: fn() => $this->cartItems()
        );
    }

    public function cartChoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartChoice::class, 'cart_id', 'id');
    }

    public function merchantBranchTypeServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Domain\AppBase\Models\MerchantBranchTypeServices::class, 'merchant_branch_id', 'merchant_branch_id');
    }

    public function cartDiscounts(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrderItem::class, 'order_id', 'id');
    }

    protected function typeServices(): Attribute
    {
        return new Attribute(
            get: fn() => $this->merchantBranchTypeServices()->where("type_service_id", $this->service_type_id)->first()
        );
    }
    // protected function type_service(): Attribute
    // {
    //     return new Attribute(
    // get: fn() => $this->merchantBranchTypeServices()->where("type_service_id", $this->service_type_id)->where('status', StatusEnum::ACTIVE)->first()
    //     );
    // }

    protected function subtotal(): Attribute
    {
        return new Attribute(
            get: fn() => $this->cartItems->reduce(function ($value, $item) {
                return $value + $item->subtotal;
            }, 0)
        );
    }

    protected function tax(): Attribute
    {
        return new Attribute(
            get: function () {
                $merchant = $this->cartItems->first()?->item?->merchant;
                $vatRate = (float) ($merchant?->country?->vat ?? 0);

                return round($this->subtotal * ($vatRate / 100), 2);
            }
        );
    }


    protected function deliveryFee(): Attribute
    {
        $setting = GeneralSetting::query()->latest()?->first();
        $km = $setting?->delivery_km;
        $delivery_fee = $setting?->delivery_fees;
        $extra_km_fees = $setting?->extra_km_fees;
        return new Attribute(
            get: fn() => $this->deliveryDistance && $setting ?
                ($this->deliveryDistance - $km <= 0 ? $delivery_fee :
                    $delivery_fee + (($this->deliveryDistance - $km) * $extra_km_fees)
                ) : 0
        );
    }

    public function getDeliveryFeeDiscountAttribute()
    {
        $check = $this->validCoupon();

        return $this->promocode && $check ?
            (
                $check->type == "flat" ?
                (
                    $check->type_discount == "delivery" ?
                    (
                        $check->discount_value >= $this->deliveryFee ? $this->delivery_fee = 0
                        :
                        $this->delivery_fee - $check->discount_value
                    )
                    : 0
                )
                : (
                    $check->type_discount == "delivery" ?
                    (
                        $this->delivery_fee * ($check->discount_value / 100) >= $check->max_discount ?
                        $this->delivery_fee =  $check->max_discount  : $this->delivery_fee = $this->delivery_fee * ($check->discount_value / 100)
                    )
                    : 0

                )
            )
            : 0;
    }



    protected function discount(): Attribute
    {
        $check = $this->validCoupon();
        if (!$this->promocode || !$check) {
            return new Attribute(get: fn() => 0);
        }

        if ($check->type === 'flat') {
            $value = $this->calculateFlatDiscount($check->discount_value, $check->type_discount);
            return new Attribute(get: fn() => $value);
        } elseif ($check->type === 'percent') {
            $value = $this->calculatePercentageDiscount($check->discount_value, $check->max_discount, $check->type_discount);
            return new Attribute(get: fn() => $value);
        }

        return new Attribute(get: fn() => 0);
    }

    protected function returnCash(): Attribute
    {
        $check = $this->validCashBack();
        if (!$this->cash_back_name || !$check) {
            return new Attribute(get: fn() => 0.0);
        }

        if ($check->type === 'flat') {
            $value = $this->calculateFlatCash($check->discount_value);
            return new Attribute(get: fn() => $value);
        } elseif ($check->type === 'percent') {
            $value = $this->calculatePercentageCash($check->discount_value, $check->max_discount);
            return new Attribute(get: fn() => $value);
        }

        return new Attribute(get: fn() => 0.0);
    }

    private function calculateFlatDiscount(float $discountValue, string $discountTarget): float
    {
        switch ($discountTarget) {
            case 'total':
                return min($discountValue, ($this->subtotal + $this->delivery_fee));
            case 'delivery':
                return min($discountValue, $this->delivery_fee);
            case 'order':
                return min($discountValue, $this->subtotal);
            default:
                return 0;
        }
    }

    private function calculatePercentageDiscount(float $discountValue, float $maxDiscount, string $discountTarget): float
    {
        switch ($discountTarget) {
            case 'total':
                $discountAmount = ($this->subtotal + $this->delivery_fee) * ($discountValue / 100);
                return min($discountAmount, $maxDiscount);
            case 'delivery':
                $discountAmount = $this->delivery_fee * ($discountValue / 100);
                return min($discountAmount, $maxDiscount);
            case 'order':
                $discountAmount = $this->subtotal * ($discountValue / 100);
                return min($discountAmount, $maxDiscount);
            default:
                return 0;
        }
    }

    private function calculateFlatCash(float $discountValue): float
    {
        return min($discountValue, ($this->subtotal + $this->delivery_fee));
    }

    private function calculatePercentageCash(float $discountValue, float $maxDiscount): float
    {
        $discountAmount = ($this->subtotal + $this->delivery_fee) * ($discountValue / 100);
        return min($discountAmount, $maxDiscount);
    }

    public function validCoupon()
    {
        $check = Promocode::query()
            ->where('status', '=', 'active')
            ->where('usages_left', '>', 0)
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            ->where('code', '=', $this->promocode)->first();
        if (!$check) return false;
        if ($this->subtotal < $check->min_order_value) return false;
        return $check;
    }

    public function validCashBack()
    {
        $check = CashBack::query()
            ->where('status', '=', 'active')
            ->where('usages_left', '>', 0)
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            ->where('name', '=', $this->cash_back_name)->first();

        if (!$check) return false;

        if ($this->subtotal < $check->min_order_value) return false;

        return $check;
    }

    protected function total(): Attribute
    {
        return new Attribute(
            get: fn() => $this->service_type_id == 2 ? ($this->subtotal - $this->discount)  : ($this->subtotal - $this->discount) + $this->delivery_fee
        );
    }

    protected function totalAfterVat(): Attribute
    {
        return new Attribute(
            get: fn() => round(
                $this->subtotal
                + $this->tax
                + ($this->service_type_id == 2 ? 0 : $this->delivery_fee)
                - $this->discount,
                2
            )
        );
    }

    public function getCurrencyAttribute(): ?string
    {
        return $this->merchant?->country?->currency?->iso ?? 'SAR';
    }

    // public function getDeliveryDistanceAttribute()
    // {
    //     $lat = resolve('lat');
    //     $long = resolve('long');
    //     if ($lat && $long) {
    //         $location = [$long, $lat];
    //         $distance = $this->branch()->getDistanceTo($location)->first()?->distance;

    //         if ($distance) return round($distance / 1000, 2);
    //         return 0;
    //     }

    //     return null;
    // }
}
