<?php

namespace App\Support\Services\Media;

use App\Contracts\Media\ImageInterface;

class MediaService implements ImageInterface
{

    public function saveAndReplace($image, $model, $collection = '', $disk = 'fileDisk'): bool
    {
        $model->clearMediaCollection($collection);
        $model->addMedia($image)->toMediaCollection($collection, $disk);

        return true;
    }

    public function saveAndReplaceMulti($images, $model, $collection = '', $disk = 'fileDisk'): bool
    {
        $model->clearMediaCollection($collection);
        collect($images)->each(fn($image) => $model->addMedia($image)
            ->toMediaCollection($collection, $disk));

        return true;
    }
}
