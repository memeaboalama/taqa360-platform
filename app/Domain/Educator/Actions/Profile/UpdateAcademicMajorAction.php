<?php

namespace Domain\Educator\Actions\Profile;

use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Domain\Educator\Models\Educator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateAcademicMajorAction
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

            if (isset($data['academic_major_id']) && !is_null($data['academic_major_id'])) {
                $majorId = AcademicMajor::where('id', $data['academic_major_id'])->orWhere('uuid', $data['academic_major_id'])->value('id');

                $data['academic_major_id'] = $majorId ? (int) $majorId : null;
            }

            $qualification = $educator->academicMajors()->updateOrCreate(
                ['id' => $editingId],
                $data
            );

            if ($upload) {
                $path = "educators/certificates";
                $disk = 'fileDisk';
                $oldFile = $qualification->certificateFile;

                $fileDetails = $this->imageService->storeImage($upload, $path, FileTypesEnum::ACADEMIC_CERTIFICATE->value, $disk, 'webp', $oldFile?->path);

                $fileData = array_merge(['collection_name' => 'academic_certificate'], $fileDetails);

                $qualification->certificateFile()->updateOrCreate(
                    ['fileable_id' => $qualification->id],
                    $fileData
                );
            }

            DB::commit();
            return $qualification;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("UpdateAcademicInfoAction Error: " . $e->getMessage());
            throw $e;
        }
    }
}
