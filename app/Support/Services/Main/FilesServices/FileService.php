<?php

namespace App\Support\Services\Main\FilesServices;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileService
{

    public function checkDirectory($folder): void
    {
        if (!File::isDirectory(storage_path("app/public/$folder"))) {
            File::makeDirectory(storage_path("app/public/$folder"), 755, true);
        }
    }

    public function saveInDir($file, $path, $disk, $type = null): array
    {
        $hashName = rand(111111, 999999) . '.' . $file->getClientOriginalExtension() ;
        Storage::disk($disk)->putFileAs("$path", $file, $hashName);

        return [
            'name' => $hashName,
            'path' => $path,
            'url' => Storage::disk($disk)->url("$path/$hashName"),
            'storage' => $disk,
            'type' => $type
        ];
    }

    public function destroyFile($fileUrl, $disk): void
    {
        Storage::disk($disk)->delete("public/$fileUrl");
    }
}
