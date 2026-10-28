<?php

namespace App\Domain\MarketPlace\Models;

use App\Support\Traits\Models\MerchantRelation;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MerchantOrderItem extends Model
{
    use HasFactory, HasTranslations, MerchantRelation;

    public array $translatable = ['item_name'];
    protected $fillable = [
        'item_name',
        'price',
        'qty',
        'item_total',
        'vat',
        'additional',
        'additional_total',
        'total_after_vat',
        'merchant_id',
        'user_id',
        'order_id',
        'merchant_branch_id',
        'item_id',
    ];

    protected $casts = [
        'total_choices' => 'float',
        'subtotal' => 'float'
    ];

    public function order() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class,'order_id');
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class,'item_id','id');
    }

    public function orderChoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantChoice::class,'order_item_id','id');
    }

    public function getItemPriceAttribute(): float
    {
        return ($this->price!=0)?$this->price: $this->item?->default_price;
    }

    public function getAddonNameAttribute(): array
    {
        $data=[];
        foreach ($this->orderChoices as $choice)  $data[]=$choice->choice?->title;
        return $data;
    }

    public function getAddonsAttribute(): array
    {
        $data=[];
        foreach ($this->orderChoices as $key => $choice)
        {
            $data[$key]['name'] = $choice->choice?->title;
            $data[$key]['price'] = $choice?->price;
        }
        return $data;
    }

    public function getSubtotalAttribute(): float
    {
        return ((($this->price!=0)?$this->price: $this->item?->default_price) * $this->qty) + $this->total_choices;
    }

    protected function totalChoices(): Attribute
    {
        return new Attribute(
            get: fn() => $this->orderChoices->reduce(function ($value, $item) {
                return $value + ($item->subtotal) * $this->qty;
            }, 0)
        );
    }

//    public function getAdditionalAttribute($value)
//    {
//        return explode(',', $value);
//    }
}
