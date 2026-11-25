<?php

namespace App\Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecificationCategoryLink extends Model
{
    protected $fillable = [
        'specification_id',
        'category_type',
        'category_id',
        'is_excluded',
    ];

    protected $casts = [
        'is_excluded' => 'boolean',
    ];

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }
}
