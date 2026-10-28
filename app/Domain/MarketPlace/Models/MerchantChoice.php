<?php

namespace App\Domain\MarketPlace\Models;

use Domain\MarketPlace\Models\AdditionGroup;
use Domain\MarketPlace\Models\Choice;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'order_id',
        'item_id',
        'addition_group_id',
        'choice_id',
        'order_item_id',
        'price',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrder::class, 'order_id', 'id');
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function order_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrderItem::class, 'order_item_id', 'id');
    }

    public function choice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Choice::class, 'choice_id', 'id');
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdditionGroup::class, 'addition_group_id', 'id');
    }

    public function getChoicePriceAttribute(): float
    {
        return //($this->price != 0) ? $this->price :
            \DB::table('addition_group_choice')
            ->where('addition_group_id', '=', $this->addition_group_id)
            ->where('choice_id', '=', $this->choice_id)
            ->first()?->price ?? 0;
    }

    public function getSubtotalAttribute(): float
    {
        return ($this->price != 0) ? $this->price :
            $this->choice_price * $this->qty;
        // return $this->choice_price * $this->qty;
    }

    public function getUniqueIdAttribute(): string
    {

        return "$this->choice_id-$this->addition_group_id";
    }
}
