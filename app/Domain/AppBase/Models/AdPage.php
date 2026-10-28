<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;


class AdPage extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'ad_pages';

    protected $fillable = [
        'name',
        'key',
        'description',
        'status',
        'sort_order',
        'meta',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'status' => StatusEnum::class,
        'meta' => 'array',
        'sort_order' => 'integer',
    ];

    public function placements(): HasMany
    {
        return $this->hasMany(AdPlacement::class, 'page_id')
            ->orderBy('sort_order');
    }

    public function activePlacements(): HasMany
    {
        return $this->placements()
            ->where('status', StatusEnum::ACTIVE);
    }

    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function isActive(): bool
    {
        return $this->status === StatusEnum::ACTIVE;
    }
}
