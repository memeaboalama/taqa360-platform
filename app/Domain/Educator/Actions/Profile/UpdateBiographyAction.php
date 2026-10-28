<?php

namespace Domain\Educator\Actions\Profile;

use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Educator\Models\Educator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateBiographyAction
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function handle(Educator $educator, array $bioData, array $socialLinks, ?UploadedFile $profileImage): void
    {
        Log::debug('Bio Data:', $bioData);
        Log::debug('Bio Data:', Arr::only($bioData, ['title','description']));
        DB::beginTransaction();
        try {
            $educator->bios()->updateOrCreate(['educator_id' => $educator->id], Arr::only($bioData, ['title','description']));

            $educator->socialLinks()->delete();
            $validLinks = array_filter($socialLinks, fn($link) => !empty($link['platform']) && !empty($link['url']));
            if (!empty($validLinks)) {
                $educator->socialLinks()->createMany($validLinks);
            }

            if ($profileImage) {
                $path = "educators/avatars";
                $disk = 'fileDisk';
                $oldFile = $educator->profileImage;
                $imageDetails = $this->imageService->storeImage($profileImage, $path, FileTypesEnum::PROFILE_IMAGE->value, $disk, 'webp', $oldFile?->path);
                $fileData = array_merge(['collection_name' => 'profile_image'], $imageDetails);
                $educator->files()->updateOrCreate(
                    ['collection_name' => 'profile_image'],
                    $fileData
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("UpdateBiographyAction Error: " . $e->getMessage());
            throw $e;
        }
    }
}
