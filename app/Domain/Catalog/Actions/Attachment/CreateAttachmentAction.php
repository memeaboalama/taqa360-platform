<?php

namespace App\Domain\Catalog\Actions\Attachment;

use App\Domain\Catalog\Models\Attachment;
use App\Domain\Catalog\Data\Attachment\CreateAttachmentData;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
// use Illuminate\Support\Facades\Storage;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class CreateAttachmentAction
{
    private ImageManager $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        // $this->imageManager = new ImageManager();
        $this->imageManager = $imageManager;
    }
    
    // public function __construct(private ImageService $imageService)
    // {
    //     $this->imageService = $imageService;
    // }
    public function execute(CreateAttachmentData $data): Attachment
    {
        return DB::transaction(function () use ($data) {
            // Find the parent model (e.g., App\Domain\Catalog\Models\Item)
            $parentModel = $data->attachable_type::findOrFail($data->attachable_id);
            $attributes = $data->only('title', 'description', 'collection_name')->toArray();
            $meta= $data->meta??[];
            $attachmentAttributes = [
                'description' => $data->description,
                'meta' => $data->meta??[]
            ];

            // Handle File Upload
            if (isset($data->file)&&$data->file) {
                $file = $data->file;
                // $path = $file->store('attachments', 'fileDisk');
                $originalPath = $file->store('attachments/originals', 'fileDisk');
                $attributes += [
                    'path' => $originalPath,
                    'file_name' => basename($originalPath),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'storage' => 'fileDisk',
                    'type' => 'file',
                ];

                if (Str::startsWith($file->getMimeType(), 'image/')) {
                    $meta['is_image']=true;
                    try {
                        $attributes['meta'] = $meta??[];
                        // $image = $this->imageManager->read($file->getRealPath());
                        $image = Image::make($file);
                        $fileName = Str::beforeLast(basename($originalPath), '.');
                        // Medium WebP
                        $webpImage = clone $image;
                        // $webpImage->scaleDown(width: 1200);
                        $encodedWebp = //$webpImage->toWebp(75)
                        $webpImage->encode('webp', 75);
                        $webpPath = 'attachments/webp/' . $fileName . '.webp';
                        Storage::disk('fileDisk')->put($webpPath, (string) $encodedWebp);
                        $attributes['webp_path'] = $webpPath;
    
                        // Square Thumbnail
                        $thumbImage = clone $image;
                        $thumbImage->resize(250,200);
                        $encodedThumb = 
                        $thumbImage->encode('webp', 80);//->resize(300, 300);
                        // $thumbImage->toWebp(80);
                        $thumbPath = 'attachments/thumbnails/' . $fileName . '.webp';
                        Storage::disk('fileDisk')->put($thumbPath, (string) $encodedThumb);
                        $attributes['thumbnail_path'] = $thumbPath;
                    } catch (\Exception $e) {
                        report($e);
                    }
                }
                else{
                    $meta['is_image']=false;
                    $attributes['meta'] = $meta??[];
                }
            } 
            // Handle External URL
            elseif ($data->url) {
                $attributes += [
                    'url' => $data->url,
                    'type' => 'url',
                ];
            }

            // Use the polymorphic relationship to create the attachment
            return $parentModel->attachments()->create($attributes);
        });
    }
}