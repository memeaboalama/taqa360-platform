<?php

namespace App\Domain\MarketPlace\Models;

use App\Support\Traits\Models\MerchantRelation;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CartDiscounts extends Model
{
    use HasFactory, HasTranslations, MerchantRelation;

    public array $translatable = ['type'];
    protected $fillable = [
        'type',
        'amount',
        'rate',
        'calculated_on',
        'order_id',
    ];

    public function order() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class,'order_id');
    }


}
