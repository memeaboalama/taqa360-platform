<?php

namespace App\Domain\AppBase\Models;

use App\Domain\User\Models\User;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\ClickActionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class AdBanner extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'ad_banners';

    protected $fillable = [
        'placement_id',
        'name',
        'title',
        'subtitle',
        'content',
        'click_action',
        'start_date',
        'end_date',
        'status',
        'priority',
        'views_count',
        'clicks_count',
        'meta',
        'created_by',
    ];

    public array $translatable = [
        'title',
        'subtitle',
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'content' => 'array',
        'click_action' => 'array',
        'status' => BannerStatusEnum::class,
        'meta' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'priority' => 'integer',
        'views_count' => 'integer',
        'clicks_count' => 'integer',
    ];

    protected $appends = [
        'desktop_image_url',
        'tablet_image_url',
        'mobile_image_url',
        'localized_title',
        'localized_subtitle',
        'click_url'
    ];

    public function placement(): BelongsTo
    {
        return $this->belongsTo(AdPlacement::class, 'placement_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getProductAttribute()
    {
        if (($this->click_action['type'] ?? null) === 'product') {
            $productId = $this->click_action['id'] ?? null;
            if ($productId) {
                return \Domain\MarketPlace\Models\Item::find($productId);
            }
        }
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', BannerStatusEnum::ACTIVE);
    }

    public function scopeWithinSchedule($query)
    {
        $now = now();

        return $query->where(function ($q) use ($now) {
            $q->where(function ($subQ) use ($now) {
                $subQ->whereNotNull('start_date')
                    ->where('start_date', '<=', $now);
            })->orWhereNull('start_date');
        })->where(function ($q) use ($now) {
            $q->where(function ($subQ) use ($now) {
                $subQ->whereNotNull('end_date')
                    ->where('end_date', '>=', $now);
            })->orWhereNull('end_date');
        });
    }

    public function scopeForPlacement($query, int $placementId)
    {
        return $query->where('placement_id', $placementId);
    }

    public function scopePrioritized($query)
    {
        return $query->orderByDesc('priority')->orderBy('id');
    }

    public function getImageUrl(string $device = 'desktop'): ?string
    {
        return $this->imageUrlForDevice($device);
    }

    public function getAltText(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        return $this->content['alt_text'][$locale] ?? null;
    }

    public function getClickActionTypeAttribute(): ?ClickActionTypeEnum
    {
        $type = $this->click_action['type'] ?? null;

        if (!$type) {
            return null;
        }

        return ClickActionTypeEnum::from($type);
    }

    public function getClickUrlAttribute(): ?string
    {
        $type = $this->click_action['type'] ?? null;
        $id = $this->click_action['id'] ?? null;

        if ($type === 'product' && $id) {
            return url(app()->getLocale() . "/product/{$id}");
        }

        if ($type === 'merchant' && $id) {
            return url(app()->getLocale() . "/ecommerce/merchant/{$id}");
        }

        if ($type === 'category' && $id) {
            // $level = $this->click_action['level'] ?? null;
            // $parentId = $this->click_action['parent_id'] ?? null;

            // $baseUrl = url(app()->getLocale() . '/categories');

            // return match($level) {
            //     1 => "{$baseUrl}?main_category={$id}",
            //     2 => "{$baseUrl}?sub_category={$id}&main_category={$parentId}",
            //     3 => "{$baseUrl}?sub_sub_category={$id}&sub_category={$parentId}",
            //     default => null
            // };
        }

        return $this->click_action['url'] ?? null;
    }

    public function getCtrAttribute(): float
    {
        if ($this->views_count === 0) {
            return 0.0;
        }

        return round(($this->clicks_count / $this->views_count) * 100, 2);
    }

    public function incrementViews(): bool
    {
        return $this->increment('views_count');
    }

    public function incrementClicks(): bool
    {
        return $this->increment('clicks_count');
    }

    public function isDisplayable(): bool
    {
        if ($this->status !== BannerStatusEnum::ACTIVE) {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    public function getDesktopImageUrlAttribute(): ?string
    {
        return $this->imageUrlForDevice('desktop');
    }

    public function getTabletImageUrlAttribute(): ?string
    {
        return $this->imageUrlForDevice('tablet');
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->imageUrlForDevice('mobile');
    }

    private function imageUrlForDevice(string $device): ?string
    {
        $path = $this->content['images'][$device] ?? null;

        if (!$path || !Storage::disk('fileDisk')->exists($path)) {
            return null;
        }

        return Storage::disk('fileDisk')->url($path);
    }

    public function getLocalizedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->getTranslation('title', $locale)
            ?? $this->title[$locale]
            ?? $this->title['en']
            ?? $this->title['ar']
            ?? '';
    }

    public function getLocalizedSubtitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->getTranslation('subtitle', $locale)
            ?? $this->subtitle[$locale]
            ?? $this->subtitle['en']
            ?? $this->subtitle['ar']
            ?? '';
    }

    public function isScheduled(): bool
    {
        return $this->status === BannerStatusEnum::SCHEDULED || ($this->start_date && $this->start_date->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->status === BannerStatusEnum::EXPIRED || ($this->end_date && $this->end_date->isPast());
    }
}
