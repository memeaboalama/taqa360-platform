<?php

namespace Domain\MarketPlace\Models;


use App\Domain\AppBase\Models\SpecificationItems;
use App\Domain\AppBase\Models\SpecificationsItems;
use App\Domain\AppBase\Models\SpecificationsPropertiesItems;
use App\Domain\Catalog\Models\Attachment;
use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\Models\CartItem;
use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\Models\MerchantOrderItem;
use App\Domain\MarketPlace\Models\ItemPrices;
use App\Support\Enum\ActiveEnum;
use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use tiagomichaelsousa\LaravelFilters\Traits\Filterable;

class Item extends Model
{
    use HasFactory, HasTranslations, BelongsToMerchant, MerchantRelation, HasStatus, SoftDeletes;
    use Filterable;

    protected $translatable = ['title', 'description'];

    protected $fillable = [
        'title',
        'status',
        'merchant_id',
        'category_id',
        'description',
        'default_price',
        'sale_price',
        'is_on_sale',
        'sale_expires_at',
        'stock',
        'calories',
        'preparing_time',
        'main_category_id',
        'sub_category_id',
        'child_category_id',
        'image'
    ];

    protected $casts = [
        'status'          => ActiveEnum::class,
        'stock'           => 'integer',
        'sale_price'      => 'decimal:2',
        'is_on_sale'      => 'boolean',
        'sale_expires_at' => 'datetime',
    ];

    protected $appends = ['all_images', 'main_image'];

    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ItemFile::class, 'fileable');
    }

    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class, 'item_id', 'id');
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrderItem::class, 'item_id', 'id');
    }
    public function specificationsItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SpecificationsItems::class, 'item_id', 'id');
    }
    public function specificationsPropertiesItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SpecificationsPropertiesItems::class, 'item_id', 'id');
    }
    public function itemPrices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemPrices::class, 'item_id', 'id');
    }
    public function activeItemPrices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemPrices::class, 'item_id', 'id')->where('status', ActiveEnum::ACTIVE->value);
    }
    public function inactiveItemPrices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemPrices::class, 'item_id', 'id')->where('status', ActiveEnum::INACTIVE->value);
    }
    public function typeServices(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Domain\AppBase\Models\TypeService::class, 'item_prices', 'item_id', 'type_service_id')->withPivot('type_service_id');
    }

    public function isInStock(): bool
    {
        return $this->stock === null || (int) $this->stock > 0;
    }

    /**
     * Scope: items actively marked as on-sale with a valid sale_price.
     * Automatically excludes expired offers.
     */
    public function scopeOnSale($query)
    {
        return $query
            ->where('is_on_sale', true)
            ->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->whereColumn('sale_price', '<', 'default_price')
            ->where(function ($q) {
                $q->whereNull('sale_expires_at')
                  ->orWhere('sale_expires_at', '>', now());
            });
    }

    /**
     * Check if the item currently has an active sale price.
     */
    public function isOnActiveSale(): bool
    {
        if (!$this->is_on_sale || !$this->sale_price || $this->sale_price <= 0) {
            return false;
        }
        if ($this->sale_price >= $this->default_price) {
            return false;
        }
        if ($this->sale_expires_at && $this->sale_expires_at->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Get the effective price (sale_price if active, otherwise default_price).
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->isOnActiveSale() ? (float) $this->sale_price : (float) $this->default_price;
    }

    /**
     * Get the discount percentage if on sale.
     */
    public function getDiscountPercentAttribute(): int
    {
        if (!$this->isOnActiveSale() || !$this->default_price) {
            return 0;
        }
        return (int) round((($this->default_price - $this->sale_price) / $this->default_price) * 100);
    }

    public function getImagePathAttribute()
    {
        return ($this->image) ? Storage::disk('fileDisk')->url($this?->image) : "";
    }

    public function additionGroups()
    {
        return $this->belongsToMany(AdditionGroup::class, 'addition_group_item', 'item_id', 'addition_group_id');
    }

    public function category()
    {
        return $this->belongsTo(MerchantCategory::class,  'category_id', 'id');
    }

    public function images()
    {
        return [
            'image_path' => ($this->image) ? Storage::disk('fileDisk')->url($this?->image) : "",
        ];
    }

    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'main_category_id', 'id');
    }
    public function subCategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id', 'id');
    }
    public function childCategory()
    {
        return $this->belongsTo(ChildCategory::class, 'child_category_id', 'id');
    }

    public function getAllImagesAttribute()
    {
        $images = [];

        if ($this->image) {
            $images[] = [
                'image_path' => Storage::disk('fileDisk')->url($this->image),
            ];
        }


        $additionalImages = $this->files()->get()->map(function ($file) {
            return [
                'image_path' => $file->url,
            ];
        });

        return array_merge($images ,$additionalImages->toArray());
    }

    public function getMainImageAttribute()
    {
        if ($this->image) {
            return [
                'image_path' => Storage::disk('fileDisk')->url($this->image),
            ];
        }

        $firstAdditional = $this->files()->first();
        if ($firstAdditional) {
            return [
                'image_path' => $firstAdditional->url,
            ];
        }

        return null;
    }

    public function getImagesAttribute()
    {
        return $this->all_images;
    }

    /**
     * The attachments associated with this item.
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachable','attachables')
                    ->withPivot('sort_order', 'is_primary') // Make pivot data accessible
                    ->orderBy('attachables.sort_order', 'asc'); // Default ordering
    }
}
