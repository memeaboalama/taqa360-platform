<?php

namespace App\Domain\EducationalRequest\Actions;

use App\Application\Educational\Enums\EducationalRequestStatus;
use App\Application\Educational\Enums\EducationalRequestType;
use App\Support\Services\ImagesServices\ImageService;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\EducationalRequest\Repositories\EducationalRequestRepository;
use Illuminate\Support\Str;
use App\Support\Enum\FileTypesEnum;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class CreateEducationalRequestAction
{
    public function __construct(
        protected EducationalRequestRepository $educationalRequestRepository,
        protected ImageService $imageService
        ){}

    public function handle(array $data): EducationalRequest
    {
        DB::beginTransaction();
        try {
            $brandFields = config('app.brand') === 'huloolcom'
                ? [
                    'academic_major_id' => $this->resolveAcademicMajorId($data['academic_major_id'] ?? null),
                    'education_level' => $data['education_level'] ?? 'Bachelor',
                ]
                : [
                    'service_id' => $data['service_id'] ?? null,
                    'project_site_type' => $data['project_site_type'] ?? null,
                    'solar_system_type' => $data['solar_system_type'] ?? null,
                ];

            $educationalRequest = $this->educationalRequestRepository->store([
                'title'             => $data['title'],
                'requester_id'      => $data['user_id'],
                'description'       => $data['description'],
                'type'              => $data['type'] ?? EducationalRequestType::GENERAL->value,
                'budget'            => $data['budget'] ?? null,
                'urgency_level'     => $data['urgency_level'] ?? 'normal',
                'additional_notes'  => $data['additional_notes'] ?? null,
            ] + $brandFields);

            if (!empty($data['attachments']) && is_array($data['attachments'])) {
                $this->handleAttachments($educationalRequest, $data['attachments']);
            }

            DB::commit();
            return $educationalRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("CreateEducationalRequestAction Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function resolveAcademicMajorId(mixed $majorReference): ?int
    {
        if ($majorReference === null || $majorReference === '') {
            return null;
        }

        $major = AcademicMajor::query()
            ->where('uuid', $majorReference)
            ->orWhere('id', $majorReference)
            ->first(['id']);

        return $major?->id;
    }

    protected function handleAttachments(EducationalRequest $educationalRequest, array $attachments): void
    {
        $path = "educational-requests/attachments";
        $disk = 'fileDisk';

        foreach ($attachments as $file) {
            if ($file instanceof UploadedFile) {
                $fileDetails = $this->imageService->storeImage($file, $path, "EDUCATION_REQUEST_ATTACHMENTS", $disk, 'webp');

                $fileData = array_merge(['collection_name' => 'educational_request_attachments'], $fileDetails);
                $educationalRequest->files()->create($fileData);
            }
        }
    }
}
