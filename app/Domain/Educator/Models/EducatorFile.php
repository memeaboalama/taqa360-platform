<?php

namespace Domain\Educator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EducatorFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
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

    protected $appends = [
        'url',
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

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
