<?php

namespace App\Domain\Catalog\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\Traits\Models\HasStatus;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Specification extends Model
{
    use HasFactory;
    // use HasTranslations;
    use HasStatus;
    protected $table = 'specifications';
    // protected $translatable = ['name', 'description'];
    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
        'meta',
        'item_id',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'meta' => 'array'
    ];


    public function properties()
    {
        return $this->hasMany(SpecificationProperty::class, 'specification_id', 'id');
    }
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }



    /**
     * Scope a query to only include master specifications (templates).
     */
    public function scopeIsMaster(Builder $query): Builder
    {
        return $query->whereNull('item_id');
    }
    /**
     * Scope a query to only include master specifications (templates).
     */
    public function scopeIsMasterOrNotInItem(Builder $query, $item_id): Builder
    {
        if (!isset($item_id) || is_null($item_id) || !is_numeric($item_id) || $item_id === 0)
            return $query->whereNull('item_id');
        return $query->whereNull('item_id')->orWhere('item_id', '!=', $item_id);
    }

    /**
     * Scope a query to only include item-specific specifications.
     */
    public function scopeIsForItem(Builder $query, int $itemId): Builder
    {
        return $query->where('item_id', $itemId);
    }


    public function getIsMasterAttribute()
    {
        return (!isset($this->item_id) || is_null($this->item_id) || !is_numeric($this->item_id) || $this->item_id === 0);
    }
}
