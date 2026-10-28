<?php

namespace App\Domain\Provider\Models;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProviderCategory extends Model
{
    use HasFactory,HasTranslations,HasStatus;

    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'main_category_id',
        'status',
        'selection',
    ];

    protected $casts = [
        'status'    =>  StatusEnum::class
    ];

    public function main_category(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class);
    }

}
