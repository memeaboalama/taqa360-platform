<?php

namespace App\Contracts\Media;

interface ImageInterface
{

    public function saveAndReplace($image, $model, $collection = '', $disk = 'fileDisk'): bool;

}
