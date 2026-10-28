<?php

namespace App\Domain\AppBase\Models;

use App\Domain\User\Models\User;
use App\Support\Enum\ProductContentStatusEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdProductContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ad_product_contents';

    protected $fillable = [
        'placement_id',
        'product_id',
        'group_key',
        'start_date',
        'end_date',
        'status',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'status' => ProductContentStatusEnum::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'priority' => 'integer',
    ];

    public function placement(): BelongsTo
    {
        return $this->belongsTo(AdPlacement::class, 'placement_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'product_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', ProductContentStatusEnum::ACTIVE);
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

    public function scopeForGroup($query, ?string $groupKey)
    {
        if ($groupKey === null) {
            return $query->whereNull('group_key');
        }

        return $query->where('group_key', $groupKey);
    }

    public function scopePrioritized($query)
    {
        return $query->orderBy('priority', 'asc')->orderBy('id', 'asc');
    }


    public function isDisplayable(): bool
    {
        if ($this->status !== ProductContentStatusEnum::ACTIVE) {
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

    public function isScheduled(): bool
    {
        return $this->status === ProductContentStatusEnum::SCHEDULED || ($this->start_date && $this->start_date->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->status === ProductContentStatusEnum::EXPIRED || ($this->end_date && $this->end_date->isPast());
    }
}
