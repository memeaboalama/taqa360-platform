<?php

namespace Domain\Educator\Actions\Profile;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Educator\Models\Educator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateBasicInfoAction
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function handle(Educator $educator, array $data, ?UploadedFile $frontImage, ?UploadedFile $backImage): Educator
    {
        DB::beginTransaction();
        try {

            if (isset($data['city_id']) && $data['city_id']) {
                $city = City::where('id', $data['city_id'])->orWhere('uuid', $data['city_id'])->first();

                $data['country_id'] = $city ? (int) $city->country_id : null;
                $data['city_id'] = $city ? (int) $city->id : null;
            }

            if (isset($data['nationality_id']) && $data['nationality_id']) {
                $nationalityId = Nationality::where('id', $data['nationality_id'])->orWhere('uuid', $data['nationality_id'])->value('id');

                $data['nationality_id'] = $nationalityId ? (int) $nationalityId : null;
            }

            $educatorData = Arr::only($data, ['name', 'email', 'phone']);
            $educator->update($educatorData);

            $profileData = Arr::except($data, ['name', 'email', 'phone', 'identityImageFront', 'identityImageBack']);
            $educator->profile()->updateOrCreate(['educator_id' => $educator->id], $profileData);
            if(isset($data['identity_type'])) {
                if ($data['identity_type'] === 'national_id') {
                    if ($frontImage) {
                        $this->storeOrUpdateFile($educator, $frontImage, 'identity_front');
                    }
                    if ($backImage) {
                        $this->storeOrUpdateFile($educator, $backImage, 'identity_back');
                    }
                    $this->deleteFile($educator, 'passport_image');
                } elseif ($data['identity_type'] === 'passport') {
                    if ($frontImage) {
                        $this->storeOrUpdateFile($educator, $frontImage, 'passport_image');
                    }
                    $this->deleteFile($educator, 'identity_front');
                    $this->deleteFile($educator, 'identity_back');
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("UpdateBasicInfoAction Error: " . $e->getMessage());
            throw $e;
        }

        return $educator->fresh(['profile', 'files']);
    }

    private function storeOrUpdateFile(Educator $educator, UploadedFile $file, string $collectionName): void
    {
        $path = "educators/basicInfo";
        $disk = 'fileDisk';
        $oldFile = $educator->files()->where('collection_name', $collectionName)->first();

        $imageDetails = $this->imageService->storeImage($file, $path, FileTypesEnum::IDENTITY->value, $disk, 'webp', $oldFile?->path);

        $fileData = array_merge(['collection_name' => $collectionName], $imageDetails);

        $educator->files()->updateOrCreate(
            ['collection_name' => $collectionName],
            $fileData
        );
    }

    private function deleteFile(Educator $educator, string $collectionName): void
    {
        $file = $educator->files()->where('collection_name', $collectionName)->first();
        if ($file) {
            $this->imageService->destroyImg($file->path, $file->storage);
            $file->delete();
        }
    }
}
