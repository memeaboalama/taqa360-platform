<?php

namespace App\Domain\Project\Actions\Project;

use App\Support\Services\ImagesServices\ImageService;
use Domain\Admin\Models\Admin;
use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectAttachment;
use Domain\Project\Models\ProjectFile;
use Domain\Project\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateAttachmentAction
{
    public function __construct(
        protected ProjectRepository $projectRepository,
        protected ImageService $imageService
    ) {}

    public function handle(Request $request, Project $project, ProjectFile $attachment)
    {
        $disk = 'fileDisk';
        $path = "projects/attachments";
        $uploader = auth()->user() ?? Admin::find(1);

        $updateData = [];

        if ($request->filled('title')) {
            $updateData['title'] = $request->title;
        }

        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            if ($file instanceof UploadedFile) {

                if (!empty($attachment->path) && Storage::disk($disk)->exists($attachment->path)) {
                    Storage::disk($disk)->delete($attachment->path);
                }

                $fileDetails = $this->imageService->storeImage($file, $path, "PROJECT_ATTACHMENT", $disk, 'webp');

                $updateData = array_merge($updateData, $fileDetails, [
                    'uploader_type' => get_class($uploader),
                    'uploader_id' => $uploader?->id,
                ]);
            }
        }

        if (empty($updateData)) {
            throw new \Exception("No data provided for update");
        }

        $updatedAttachment = $this->projectRepository->updateAttachment($attachment, $updateData);

        return $updatedAttachment;
    }
}
