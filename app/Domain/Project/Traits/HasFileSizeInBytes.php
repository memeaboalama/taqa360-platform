<?php

namespace Domain\Project\Traits;

use Illuminate\Support\Facades\Storage;

trait HasFileSizeInBytes
{
    public function sizeInBytes(): ?int
    {
        $storedSize = $this->getRawOriginal('size');
        $path = $this->getRawOriginal('path');
        $storage = $this->getRawOriginal('storage');

        if ($storedSize !== null && (float) $storedSize >= 1024) {
            return (int) round((float) $storedSize);
        }

        if ($path && $storage) {
            $disk = Storage::disk($storage);
            if ($disk->exists($path)) {
                return (int) $disk->size($path);
            }
        }

        if ($storedSize === null) {
            return null;
        }

        $storedSize = (float) $storedSize;

        return (int) round((float) $storedSize * 1024 * 1024);
    }
}
