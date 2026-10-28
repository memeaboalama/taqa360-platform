<?php

namespace App\Domain\Project\Actions\Project;

use App\Support\Services\ImagesServices\ImageService;
use Domain\Admin\Models\Admin;
use Domain\Project\Models\Project;
use Domain\Project\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class StoreAttachmentAction
{
    public function __construct(
        protected ProjectRepository $projectRepository,
        protected ImageService $imageService
    ){}

    public function handle(Request $request, Project $project)
    {
        $path = "projects/attachments";
        $disk = 'fileDisk';

        $uploader = auth()->user() ?? Admin::find(1);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            if ($file instanceof UploadedFile) {

                    $fileDetails = $this->imageService->storeImage($file, $path, "PROJECT_ATTACHMENT", $disk, 'webp');
                    $fileData = array_merge([
                        'title' => $request->title ?? $fileDetails['original_name'],
                        'description' => $request->description,
                        'collection_name' => 'project_attachment',
                        'uploader_type' => get_class($uploader),
                        'uploader_id' => $uploader?->id,
                    ], $fileDetails);

                    $attachment = $this->projectRepository->storeAttachment($project, $fileData);
                    // event(new ProjectUpdated($project));
                    return $attachment;
            }
        }
        throw new \Exception("No attachment found in the request");
    }
}
