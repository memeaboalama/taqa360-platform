<?php

namespace Domain\EducationalRequest\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EducationalRequestFile extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'file_name',
        'original_name',
        'mime_type',
        'collection_name',
        'type',
        'storage',
        'url',
        'path',
        'order_column',
        'size',
    ];

    protected $casts = [
        'size' => 'float',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->path && $this->storage) {
            return Storage::disk($this->storage)->url($this->path);
        }
        return null;
    }
}
