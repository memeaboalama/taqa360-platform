<?php

namespace App\Support\Services\Main\FilesServices;

use App\Contracts\Services\FilesContract;
use App\Support\Enum\FileTypesEnum;

class LocalFiles extends FileService implements FilesContract
{

    public function storeFile($file, string $path, FileTypesEnum $type, string $oldPath = null , string $disk = 'local'): array
    {
        // checkDir
        $this->checkDirectory($path);
        $oldPath ? $this->destroyFile($oldPath, $path) : null;
        return $this->saveInDir($file, $path, $disk, $type);
    }

}
