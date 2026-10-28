<?php

namespace Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MerchantFile extends Model
{
    use HasFactory;

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
        'order_column' => 'integer',
        'size'         => 'float',
    ];

    public function fileable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Resolved public link for this file.
     *
     * Prefers the local fileDisk copy and falls back to the `url` column that
     * ImageService stored at upload time. The fallback matters because the
     * fileDisk root lives outside the public web root, so the generated link
     * 404s on hosts where the files were never synced locally.
     */
    public function getPublicUrlAttribute(): ?string
    {
        if ($this->path) {
            $disk = $this->storage ?: 'fileDisk';

            try {
                if (Storage::disk($disk)->exists($this->path)) {
                    return Storage::disk($disk)->url($this->path);
                }
            } catch (\Throwable) {
                // Fall through to the stored url below.
            }
        }

        return $this->url ?: null;
    }

    /**
     * Whether this file should be previewed as an image.
     */
    public function getIsImageAttribute(): bool
    {
        if ($this->mime_type) {
            return str_starts_with($this->mime_type, 'image/');
        }

        if ($this->path) {
            return in_array(strtolower(pathinfo($this->path, PATHINFO_EXTENSION)), [
                'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp',
            ], true);
        }

        return false;
    }

    /**
     * Human readable label: the uploader's filename when known, else the
     * stored file name without its directory and extension noise.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->original_name) {
            return $this->original_name;
        }

        return $this->file_name ?: basename((string) $this->path);
    }
}
