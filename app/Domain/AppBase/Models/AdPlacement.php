<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;
use Domain\AppBase\Models\AdCategoryContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AdPlacement extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'ad_placements';

    protected $fillable = [
        'page_id',
        'name',
        'key',
        'location',
        'type',
        'settings',
        'status',
        'sort_order',
        'meta',
    ];

    public array $translatable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
        'type' => PlacementTypeEnum::class,
        'settings' => 'array',
        'status' => StatusEnum::class,
        'meta' => 'array',
        'sort_order' => 'integer',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(AdPage::class, 'page_id');
    }

    public function banners(): HasMany
    {
        return $this->hasMany(AdBanner::class, 'placement_id')
            ->orderByDesc('priority')
            ->orderBy('id');
    }

    public function productContents(): HasMany
    {
        return $this->hasMany(AdProductContent::class, 'placement_id');
    }

    public function merchantContents(): HasMany
    {
        return $this->hasMany(AdMerchantContent::class, 'placement_id');
    }

    public function categoryContents(): HasMany
    {
        return $this->hasMany(AdCategoryContent::class, 'placement_id');
    }

    public function getItemsCountAttribute(): int
    {
        return match ($this->type) {
            PlacementTypeEnum::PRODUCT_TABS,
            PlacementTypeEnum::PRODUCT_SLIDER,
            PlacementTypeEnum::PRODUCT_SIDEBAR => (int) ($this->product_contents_count ?? 0),
            PlacementTypeEnum::CAROUSEL => (int) ($this->category_contents_count ?? 0),
            PlacementTypeEnum::MERCHANT_SLIDER => (int) ($this->merchant_contents_count ?? 0),
            default => (int) ($this->banners_count ?? 0),
        };
    }

    public function activeProductContents(): HasMany
    {
        return $this->hasMany(AdProductContent::class, 'placement_id')
            ->active()
            ->withinSchedule()
            ->prioritized();
    }

    public function activeBanners(): HasMany
    {
        return $this->banners()->active()->withinSchedule();
    }

    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE);
    }

    public function scopeForPage($query, int $pageId)
    {
        return $query->where('page_id', $pageId);
    }

    public function scopeOfType($query, PlacementTypeEnum $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function isActive(): bool
    {
        return $this->status === StatusEnum::ACTIVE;
    }

    public function getMaxBannersAttribute(): ?int
    {
        return $this->settings['max_items'] ?? null;
    }

    public function canAcceptMoreBanners(): bool
    {
        $maxBanners = $this->max_banners;

        if ($maxBanners === null) {
            return true;
        }

        return $this->banners()->count() < $maxBanners;
    }

    public function getDeviceSettings(string $device): ?array
    {
        return $this->settings['display']['devices'][$device] ?? null;
    }

    public function isVisibleOnDevice(string $device): bool
    {
        $deviceSettings = $this->getDeviceSettings($device);

        return $deviceSettings['enabled'] ?? false;
    }
}
