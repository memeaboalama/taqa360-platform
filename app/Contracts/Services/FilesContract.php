<?php

namespace App\Contracts\Services;


use App\Support\Enum\FileTypesEnum;

interface FilesContract
{
    /**
     * @param $file
     * @param string $path
     * @param string $oldPath
     * @return array
     */
    public function storeFile($file, string $path, FileTypesEnum $type, string $oldPath): array;


    /**
     * @param $file
     * @param string $path
     * @param string $disk
     * @param $type
     * @return array
     */
    public function saveInDir($file, string $path, string $disk, $type = null): array;


    /**
     * @param string $folder
     * @return void
     */
    public function checkDirectory(string $folder): void;

    /**
     * @param string $fileUrl
     * @param string $disk
     * @return void
     */
    public function destroyFile(string $fileUrl, string $disk): void;
}
