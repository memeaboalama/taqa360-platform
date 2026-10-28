<?php

namespace Domain\Project\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class TaskFile extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'uuid',
        'title',
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
        'uploader_type',
        'uploader_id',
        'uploaded_at',
    ];

    protected $casts = [
        'size' => 'float',
        'uploaded_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader()
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
            if (empty($model->uploaded_at)) {
                $model->uploaded_at = now();
            }
        });
    }
}
