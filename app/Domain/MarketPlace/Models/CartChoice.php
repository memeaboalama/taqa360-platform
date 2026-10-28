<?php

namespace Domain\MarketPlace\Models;

use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'cart_id',
        'item_id',
        'addition_group_id',
        'choice_id',
        'cart_item_id',
    ];

    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function cart_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id', 'id');
    }

    public function choice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Choice::class, 'choice_id', 'id');
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdditionGroup::class, 'addition_group_id', 'id');
    }

    public function getPriceAttribute(): float
    {
        return \DB::table('addition_group_choice')
            ->where('addition_group_id', '=', $this->addition_group_id)
            ->where('choice_id', '=', $this->choice_id)
            ->first()?->price ?? 0;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->qty;
    }

    public function getUniqueIdAttribute(): string
    {

        return "$this->choice_id-$this->addition_group_id";


    }
}
