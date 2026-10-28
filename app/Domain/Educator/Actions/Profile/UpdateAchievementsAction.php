<?php

namespace Domain\Educator\Actions\Profile;

use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Educator\Models\Educator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateAchievementsAction
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function handle(Educator $educator, array $data, ?int $editingId, ?UploadedFile $upload)
    {
        DB::beginTransaction();
        try {
            $achievement = $educator->achievements()->updateOrCreate(['id' => $editingId], $data);

            if ($upload) {
                $path = "educators/achievements";
                $disk = 'fileDisk';
                $oldFile = $achievement->file;

                $fileDetails = $this->imageService->storeImage($upload, $path, FileTypesEnum::ACHIEVEMENT_ATTACHMENT->value, $disk, 'webp', $oldFile?->path);

                $fileData = array_merge(['collection_name' => 'achievement_attachment'], $fileDetails);

                $achievement->file()->updateOrCreate(
                    ['fileable_id' => $achievement->id],
                    $fileData,
                );
            }

            DB::commit();
            return $achievement;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("UpdateAchievementsAction Save Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Educator $educator, int $id): void
    {
        $achievement = $educator->achievements()->find($id);
        if ($achievement) {
            if ($achievement->file) {
                $this->imageService->destroyImg($achievement->file->path, $achievement->file->storage);
                $achievement->file->delete();
            }
            $achievement->delete();
        }
    }
}
