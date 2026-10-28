<?php

namespace App\Domain\AppBase\Models\Edu;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelPackageTools\Concerns\Package\HasTranslations;

class Link extends Model
{
    use SoftDeletes, HasUuid;
    protected $fillable = [
        'title',
        'url',
        'collection_name',
        'type',
    ];

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }
}
