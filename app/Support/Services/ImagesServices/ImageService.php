<?php

namespace App\Support\Services\ImagesServices;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;

class ImageService
{
    public function checkDirectory($folder, $disk = 'fileDisk'): void
    {
        if (!Storage::disk($disk)->exists($folder)) {
            Storage::disk($disk)->makeDirectory($folder);
        }
    }

    public function storeImage(UploadedFile $file, string $path, $type, string $disk = 'fileDisk', string $imageType = 'webp', ?string $oldPath = null, ?int $order = null): array
    {
        if (!$file->isValid()) {
            throw new \Exception("Invalid file upload.");
        }

        $mime = $file->getMimeType();

        $nonImageMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
            'application/zip',
            'application/x-rar-compressed',
            'application/octet-stream',
        ];

        $this->checkDirectory($path, $disk);

        $name = $file->hashName();

        if ($oldPath) {
            $this->destroyImg($oldPath, $disk);
        }

        if (str_starts_with($mime, 'image/') && !in_array($mime, $nonImageMimes)) {
            try {
                $image = Image::make($file);
                $name = pathinfo($name, PATHINFO_FILENAME) . '.webp';
                Storage::disk($disk)->put("$path/$name", $image->encode('webp', config('image.webp.quality', 80)));
                $mime = 'image/webp';
            } catch (\Exception $e) {
                Log::info("error", ['message' => $e->getMessage()]);
                Storage::disk($disk)->putFileAs($path, $file, $name);
            }
        } else {
            Storage::disk($disk)->putFileAs($path, $file, $name);
        }

        return [
            'original_name' => $file->getClientOriginalName(),
            'file_name' => $name,
            'path' => "$path/$name",
            'url' => Storage::disk($disk)->url("$path/$name"),
            'storage' => $disk,
            'type' => $type,
            'size' => number_format($file->getSize() / 1048576, 2),
            'mime_type' => $mime,
            'order_column' => $order,
        ];
    }

    public function destroyImg(string $imgPath, string $disk = 'fileDisk'): void
    {
        if (Storage::disk($disk)->exists($imgPath)) {
            Storage::disk($disk)->delete($imgPath);
        }
    }
}
