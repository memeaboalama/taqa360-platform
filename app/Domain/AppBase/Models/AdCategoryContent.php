<?php

namespace Domain\AppBase\Models;

use App\Domain\AppBase\Models\AdPlacement;
use Domain\MarketPlace\Models\Catalog\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCategoryContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'placement_id',
        'category_id',
        'priority',
        'status',
        'created_by',
    ];

    protected $casts = [
        'status' => \App\Support\Enum\BannerStatusEnum::class,
    ];

    public function placement()
    {
        return $this->belongsTo(AdPlacement::class, 'placement_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
